#!/usr/bin/env bash

#
# TYPO3 core test runner based on docker.
#

trap 'cleanUp;exit 2' SIGINT

waitFor() {
    local HOST=${1}
    local PORT=${2}
    local TESTCOMMAND="
        COUNT=0;
        while ! nc -z ${HOST} ${PORT}; do
            if [ \"\${COUNT}\" -gt 20 ]; then
              echo \"Can not connect to ${HOST} port ${PORT}. Aborting.\";
              exit 1;
            fi;
            sleep 1;
            COUNT=\$((COUNT + 1));
        done;
    "
    ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name wait-for-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${IMAGE_ALPINE} /bin/sh -c "${TESTCOMMAND}"
    if [[ $? -gt 0 ]]; then
        kill -SIGINT -$$
    fi
}

cleanUp() {
    ATTACHED_CONTAINERS=$(${CONTAINER_BIN} ps --filter network=${NETWORK} --format='{{.Names}}')
    for ATTACHED_CONTAINER in ${ATTACHED_CONTAINERS}; do
        ${CONTAINER_BIN} kill ${ATTACHED_CONTAINER} >/dev/null
    done
    ${CONTAINER_BIN} network rm ${NETWORK} >/dev/null
}

handleDbmsOptions() {
    # -a, -d, -i depend on each other. Validate input combinations and set defaults.
    case ${DBMS} in
        mariadb)
            [ -z "${DATABASE_DRIVER}" ] && DATABASE_DRIVER="mysqli"
            if [ "${DATABASE_DRIVER}" != "mysqli" ] && [ "${DATABASE_DRIVER}" != "pdo_mysql" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            [ -z "${DBMS_VERSION}" ] && DBMS_VERSION="10.3"
            if ! [[ ${DBMS_VERSION} =~ ^(10.3|10.4|10.5|10.6|10.7|10.8|10.9|10.10|10.11|11.0|11.1)$ ]]; then
                echo "Invalid combination -d ${DBMS} -i ${DBMS_VERSION}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
        mysql)
            [ -z "${DATABASE_DRIVER}" ] && DATABASE_DRIVER="mysqli"
            if [ "${DATABASE_DRIVER}" != "mysqli" ] && [ "${DATABASE_DRIVER}" != "pdo_mysql" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            [ -z "${DBMS_VERSION}" ] && DBMS_VERSION="8.0"
            if ! [[ ${DBMS_VERSION} =~ ^(8.0)$ ]]; then
                echo "Invalid combination -d ${DBMS} -i ${DBMS_VERSION}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
        postgres)
            if [ -n "${DATABASE_DRIVER}" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            [ -z "${DBMS_VERSION}" ] && DBMS_VERSION="10"
            if ! [[ ${DBMS_VERSION} =~ ^(10|11|12|13|14|15|16)$ ]]; then
                echo "Invalid combination -d ${DBMS} -i ${DBMS_VERSION}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
        sqlite)
            if [ -n "${DATABASE_DRIVER}" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            if [ -n "${DBMS_VERSION}" ]; then
                echo "Invalid combination -d ${DBMS} -i ${DATABASE_DRIVER}" >&2
                echo >&2
                echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
        *)
            echo "Invalid option -d ${DBMS}" >&2
            echo >&2
            echo "Use \".Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
            exit 1
            ;;
    esac
}

cleanCacheFiles() {
    echo -n "Clean caches ... "
    rm -rf \
        .Build/.cache \
        .php-cs-fixer.cache
    echo "done"
}

cleanTestFiles() {
    # test related
    echo -n "Clean test related files ... "
    rm -rf \
        .Build/public/typo3temp/var/tests/
    echo "done"
}

cleanRenderedDocumentationFiles() {
    echo -n "Clean rendered documentation files ... "
    rm -rf \
        Documentation-GENERATED-temp
    echo "done"
}

cleanComposer() {
  rm -rf \
    .Build/vendor \
    .Build/bin \
    composer.lock
}

stashComposerFiles() {
    cp composer.json composer.json.orig
    if [ -f "composer.json.testing" ]; then
        cp composer.json composer.json.orig
    fi
}

restoreComposerFiles() {
    cp composer.json composer.json.testing
    mv composer.json.orig composer.json
}

loadHelp() {
    # Load help text into $HELP
    read -r -d '' HELP <<EOF
TYPO3 core test runner. Execute unit, functional and other test suites in
a container based test environment. Handles execution of single test files,
sending xdebug information to a local IDE and more.

Usage: $0 [options] [file]

Options:
    -s <...>
        Specifies which test suite to run
            - cgl: cgl test and fix all php files
            - clean: clean up build and testing related files
            - cleanRenderedDocumentation: clean up rendered documentation files and folders (Documentation-GENERATED-temp)
            - composer: Execute "composer" command, using -e for command arguments pass-through, ex. -e "ci:php:stan"
            - composerInstall: "composer update", handy if host has no PHP
            - composerInstallLowest: "composer update", handy if host has no PHP
            - composerInstallHighest: "composer update", handy if host has no PHP
            - coveralls: Generate coverage
            - docsGenerate: Renders the extension ReST documentation.
            - rector: Run rector
            - fractor: Run Fractor
            - functional: functional tests
            - lint: PHP linting
            - unit: PHP unit tests

    -a <mysqli|pdo_mysql>
        Only with -s functional|functionalDeprecated
        Specifies to use another driver, following combinations are available:
            - mysql
                - mysqli (default)
                - pdo_mysql
            - mariadb
                - mysqli (default)
                - pdo_mysql

    -b <docker|podman>
        Container environment:
            - docker (default)
            - podman

    -d <sqlite|mariadb|mysql|postgres>
        Only with -s functional|functionalDeprecated
        Specifies on which DBMS tests are performed
            - sqlite: (default): use sqlite
            - mariadb: use mariadb
            - mysql: use MySQL
            - postgres: use postgres

    -i version
        Specify a specific database version
        With "-d mariadb":
            - 10.3   short-term, maintained until 2023-05-25 (default)
            - 10.4   short-term, maintained until 2024-06-18
            - 10.5   short-term, maintained until 2025-06-24
            - 10.6   long-term, maintained until 2026-06
            - 10.7   short-term, no longer maintained
            - 10.8   short-term, maintained until 2023-05
            - 10.9   short-term, maintained until 2023-08
            - 10.10  short-term, maintained until 2023-11
            - 10.11  long-term, maintained until 2028-02
            - 11.0   development series
            - 11.1   short-term development series
        With "-d mysql":
            - 8.0   maintained until 2026-04 (default)
        With "-d postgres":
            - 10    unmaintained since 2022-11-10 (default)
            - 11    unmaintained since 2023-11-09
            - 12    maintained until 2024-11-14
            - 13    maintained until 2025-11-13
            - 14    maintained until 2026-11-12
            - 15    maintained until 2027-11-11
            - 16    maintained until 2028-11-09

    -t <12|13>
        Only with -s composerInstall|composerInstallMin|composerInstallMax
        Specifies the TYPO3 CORE Version to be used
            - 12: use TYPO3 v12 (default)
            - 13: use TYPO3 v13

    -p <8.1|8.2|8.3|8.4>
        Specifies the PHP minor version to be used
            - 8.1: use PHP 8.1 (default)
            - 8.2: use PHP 8.2
            - 8.3: use PHP 8.3
            - 8.4: use PHP 8.4

    -e "<phpunit options>"
        Only with -s docsGenerate|functional|unit
        Additional options to send to phpunit (unit & functional tests). For phpunit,
        options starting with "--" must be added after options starting with "-".
        Example -e "--filter classCanBeRegistered" to enable verbose output AND filter tests
        named "classCanBeRegistered"

        DEPRECATED - pass arguments after the `--` separator directly. For example, instead of
            Build/Scripts/runTests.sh -s unit -e "--filter classCanBeRegistered"
        use
            Build/Scripts/runTests.sh -s unit -- --filter classCanBeRegistered

    -x
        Only with -s functional|functionalDeprecated|unit|unitDeprecated|unitRandom
        Send information to host instance for test or system under test break points. This is especially
        useful if a local PhpStorm instance is listening on default xdebug port 9003. A different port
        can be selected with -y

    -y <port>
        Send xdebug information to a different port than default 9003 if an IDE like PhpStorm
        is not listening on default port.

    -n
        Only with -s cgl|composerNormalize|fractor
        Activate dry-run in CGL check that does not actively change files and only prints broken ones.

    -u
        Update existing typo3/core-testing-*:latest container images and remove dangling local volumes.
        New images are published once in a while and only the latest ones are supported by core testing.
        Use this if weird test errors occur. Also removes obsolete image versions of typo3/core-testing-*.

    -h
        Show this help.

Examples:
    # Run all core unit tests
    ./Build/Scripts/runTests.sh -s unit

    # Run all core units tests and enable xdebug (have a PhpStorm listening on port 9003!)
    ./Build/Scripts/runTests.sh -x -s unit

    # Run unit tests in phpunit verbose mode with xdebug on PHP 8.1 and filter for test canRetrieveValueWithGP
    ./Build/Scripts/runTests.sh -x -p 8.1 -- --filter 'classCanBeRegistered'

    # Run functional tests in phpunit with a filtered test method name in a specified file
    # example will currently execute two tests, both of which start with the search term
    ./Build/Scripts/runTests.sh -s functional -- --filter 'findRecordByImportSource' Tests/Functional/Repository/CategoryRepositoryTest.php

    # Run functional tests on postgres with xdebug, php 8.1 and execute a restricted set of tests
    ./Build/Scripts/runTests.sh -x -p 8.1 -s functional -d postgres -- Tests/Functional/Repository/CategoryRepositoryTest.php

    # Run functional tests on postgres 11
    ./Build/Scripts/runTests.sh -s functional -d postgres -i 11
EOF
}

# Test if at least one of the supported container binaries exists, else exit out with error
if ! type "docker" >/dev/null 2>&1 && ! type "podman" >/dev/null 2>&1; then
    echo "This script relies on docker or podman. Please install at least one of them" >&2
    exit 1
fi

# Go to the directory this script is located, so everything else is relative
# to this dir, no matter from where this script is called, then go up two dirs.
THIS_SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null && pwd)"
cd "$THIS_SCRIPT_DIR" || exit 1
cd ../../ || exit 1
ROOT_DIR="${PWD}"

# Option defaults
TEST_SUITE=""
TYPO3_VERSION="12"
DBMS="sqlite"
DBMS_VERSION=""
PHP_VERSION="8.1"
PHP_XDEBUG_ON=0
PHP_XDEBUG_PORT=9003
EXTRA_TEST_OPTIONS=""
DRY_RUN=0
DATABASE_DRIVER=""
CONTAINER_BIN=""
COMPOSER_ROOT_VERSION="12.0.0-dev"
CONTAINER_INTERACTIVE="-it --init"
HOST_UID=$(id -u)
HOST_PID=$(id -g)
USERSET=""
SUFFIX=$(echo $RANDOM)
NETWORK="georgringer-news-${SUFFIX}"
CI_PARAMS="${CI_PARAMS:-}"
CONTAINER_HOST="host.docker.internal"
PHPSTAN_CONFIG_FILE="phpstan.neon"
IS_CI=0

# Option parsing updates above default vars
# Reset in case getopts has been used previously in the shell
OPTIND=1
# Array for invalid options
INVALID_OPTIONS=()
# Simple option parsing based on getopts (! not getopt)
while getopts "a:b:s:d:i:p:e:t:xy:nhu" OPT; do
    case ${OPT} in
        s)
            TEST_SUITE=${OPTARG}
            ;;
        a)
            DATABASE_DRIVER=${OPTARG}
            ;;
        b)
            if ! [[ ${OPTARG} =~ ^(docker|podman)$ ]]; then
                INVALID_OPTIONS+=("-b ${OPTARG}")
            fi
            CONTAINER_BIN=${OPTARG}
            ;;
        d)
            DBMS=${OPTARG}
            ;;
        i)
            DBMS_VERSION=${OPTARG}
            ;;
        p)
            PHP_VERSION=${OPTARG}
            if ! [[ ${PHP_VERSION} =~ ^(8.1|8.2|8.3|8.4)$ ]]; then
                INVALID_OPTIONS+=("-p ${OPTARG}")
            fi
            ;;
        e)
            EXTRA_TEST_OPTIONS=${OPTARG}
            ;;
        t)
            TYPO3_VERSION=${OPTARG}
            if ! [[ ${TYPO3_VERSION} =~ ^(12|13)$ ]]; then
                INVALID_OPTIONS+=("-t ${OPTARG}")
            fi
            ;;
        x)
            PHP_XDEBUG_ON=1
            ;;
        y)
            PHP_XDEBUG_PORT=${OPTARG}
            ;;
        n)
            DRY_RUN=1
            ;;
        h)
            loadHelp
            echo "${HELP}"
            exit 0
            ;;
        u)
            TEST_SUITE=update
            ;;
        \?)
            INVALID_OPTIONS+=("-${OPTARG}")
            ;;
        :)
            INVALID_OPTIONS+=("-${OPTARG}")
            ;;
    esac
done

# Exit on invalid options
if [ ${#INVALID_OPTIONS[@]} -ne 0 ]; then
    echo "Invalid option(s):" >&2
    for I in "${INVALID_OPTIONS[@]}"; do
        echo ${I} >&2
    done
    echo >&2
    echo "call \".Build/Scripts/runTests.sh -h\" to display help and valid options"
    exit 1
fi

handleDbmsOptions

# ENV var "CI" is set by gitlab-ci. Use it to force some CI details.
if [ "${CI}" == "true" ]; then
    IS_CI=1
    CONTAINER_INTERACTIVE=""
fi

# determine default container binary to use: 1. podman 2. docker
if [[ -z "${CONTAINER_BIN}" ]]; then
    if type "podman" >/dev/null 2>&1; then
        CONTAINER_BIN="podman"
    elif type "docker" >/dev/null 2>&1; then
        CONTAINER_BIN="docker"
    fi
fi

if [ $(uname) != "Darwin" ] && [ "${CONTAINER_BIN}" == "docker" ]; then
    # Run docker jobs as current user to prevent permission issues. Not needed with podman.
    USERSET="--user $HOST_UID"
fi

if ! type ${CONTAINER_BIN} >/dev/null 2>&1; then
    echo "Selected container environment \"${CONTAINER_BIN}\" not found. Please install \"${CONTAINER_BIN}\" or use -b option to select one." >&2
    exit 1
fi

# Create .cache dir: composer need this.
mkdir -p .cache
mkdir -p .Build/public/typo3temp/var/tests

IMAGE_PHP="ghcr.io/typo3/core-testing-$(echo "php${PHP_VERSION}" | sed -e 's/\.//'):latest"
IMAGE_ALPINE="docker.io/alpine:3.8"
IMAGE_DOCS="ghcr.io/typo3-documentation/render-guides:latest"
IMAGE_MARIADB="docker.io/mariadb:${DBMS_VERSION}"
IMAGE_MYSQL="docker.io/mysql:${DBMS_VERSION}"
IMAGE_POSTGRES="docker.io/postgres:${DBMS_VERSION}-alpine"

# Set $1 to first mass argument, this is the optional test file or test directory to execute
shift $((OPTIND - 1))

${CONTAINER_BIN} network create ${NETWORK} >/dev/null

if [ "${CONTAINER_BIN}" == "docker" ]; then
    CONTAINER_COMMON_PARAMS="${CONTAINER_INTERACTIVE} --rm --network ${NETWORK} --add-host "${CONTAINER_HOST}:host-gateway" ${USERSET} -v ${ROOT_DIR}:${ROOT_DIR} -w ${ROOT_DIR}"
else
    # podman
    CONTAINER_HOST="host.containers.internal"
    CONTAINER_COMMON_PARAMS="${CONTAINER_INTERACTIVE} ${CI_PARAMS} --rm --network ${NETWORK} -v ${ROOT_DIR}:${ROOT_DIR} -w ${ROOT_DIR}"
fi

if [ ${PHP_XDEBUG_ON} -eq 0 ]; then
    XDEBUG_MODE="-e XDEBUG_MODE=off"
    XDEBUG_CONFIG=" "
else
    XDEBUG_MODE="-e XDEBUG_MODE=debug -e XDEBUG_TRIGGER=foo"
    XDEBUG_CONFIG="client_port=${PHP_XDEBUG_PORT} client_host=host.docker.internal"
fi

# Suite execution
case ${TEST_SUITE} in
    cgl)
        DRY_RUN_OPTIONS=''
        if [ "${DRY_RUN}" -eq 1 ]; then
            DRY_RUN_OPTIONS='--dry-run --diff'
        fi
        COMMAND="php -dxdebug.mode=off .Build/bin/php-cs-fixer fix -v ${DRY_RUN_OPTIONS} --config=Build/php-cs-fixer/php-cs-fixer.php --using-cache=no"
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-command-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/sh -c "${COMMAND}"
        SUITE_EXIT_CODE=$?
        ;;
    clean)
        rm -rf \
          var/ \
          .cache \
          composer.lock \
          .Build/ \
          Tests/Acceptance/Support/_generated/ \
          composer.json.testing \
          Documentation-GENERATED-temp
        ;;
    composer)
        COMMAND=(composer "$@")
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-command-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} "${COMMAND[@]}"
        SUITE_EXIT_CODE=$?
        ;;
    composerInstall)
        cleanComposer
        stashComposerFiles
        COMMAND=(composer install "$@")
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-install-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} "${COMMAND[@]}"
        SUITE_EXIT_CODE=$?
        restoreComposerFiles
        ;;
    composerInstallHighest)
        cleanComposer
        stashComposerFiles
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-install-highest-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/bash -c "
            if [ ${TYPO3_VERSION} -eq 12 ]; then
              composer require --no-ansi --no-interaction --no-progress --no-install \
                typo3/cms-core:^12.4.37 || exit 1
            fi
            if [ ${TYPO3_VERSION} -eq 13 ]; then
              composer require --no-ansi --no-interaction --no-progress --no-install \
                typo3/cms-core:^13.1 || exit 1
            fi
            composer update --no-progress --no-interaction  || exit 1
            composer show || exit 1
        "
        SUITE_EXIT_CODE=$?
        restoreComposerFiles
        ;;
    composerInstallLowest)
        cleanComposer
        stashComposerFiles
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-install-lowest-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/bash -c "
            if [ ${TYPO3_VERSION} -eq 12 ]; then
              composer require --no-ansi --no-interaction --no-progress --no-install \
                typo3/cms-core:^12.4.2 || exit 1
            fi
            if [ ${TYPO3_VERSION} -eq 13 ]; then
              composer require --no-ansi --no-interaction --no-progress --no-install \
                typo3/cms-core:^13.4.15 || exit 1
            fi
            composer update --no-ansi --no-interaction --no-progress --with-dependencies --prefer-lowest || exit 1
            composer show || exit 1
        "
        SUITE_EXIT_CODE=$?
        restoreComposerFiles
        ;;
    docsGenerate)
        mkdir -p Documentation-GENERATED-temp
        chown -R ${HOST_UID}:${HOST_PID} Documentation-GENERATED-temp
        COMMAND=(--config=Documentation --fail-on-log ${EXTRA_TEST_OPTIONS} "$@")
        ${CONTAINER_BIN} run ${CONTAINER_INTERACTIVE} --rm --pull always ${USERSET} -v "${ROOT_DIR}":/project ${IMAGE_DOCS} "${COMMAND[@]}"
        SUITE_EXIT_CODE=$?
        ;;
    coveralls)
        COMMAND=(php -dxdebug.mode=coverage ./.Build/bin/php-coveralls --coverage_clover=./.Build/logs/clover.xml --json_path=./.Build/logs/coveralls-upload.json -v)
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-coverals-${SUFFIX} -e XDEBUG_MODE=coverage -e XDEBUG_TRIGGER=foo -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} "${COMMAND[@]}"
        SUITE_EXIT_CODE=$?
        ;;
    functional)
        COMMAND=(.Build/bin/phpunit -c Build/phpunit/FunctionalTests.xml --exclude-group not-${DBMS} ${EXTRA_TEST_OPTIONS} "$@")
        case ${DBMS} in
            mariadb)
                echo "Using driver: ${DATABASE_DRIVER}"
                ${CONTAINER_BIN} run --rm ${CI_PARAMS} --name mariadb-func-${SUFFIX} --network ${NETWORK} -d -e MYSQL_ROOT_PASSWORD=funcp --tmpfs /var/lib/mysql/:rw,noexec,nosuid ${IMAGE_MARIADB} >/dev/null
                waitFor mariadb-func-${SUFFIX} 3306
                CONTAINERPARAMS="-e typo3DatabaseDriver=${DATABASE_DRIVER} -e typo3DatabaseName=func_test -e typo3DatabaseUsername=root -e typo3DatabaseHost=mariadb-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name functional-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${CONTAINERPARAMS} ${IMAGE_PHP} "${COMMAND[@]}"
                SUITE_EXIT_CODE=$?
                ;;
            mysql)
                echo "Using driver: ${DATABASE_DRIVER}"
                ${CONTAINER_BIN} run --rm ${CI_PARAMS} --name mysql-func-${SUFFIX} --network ${NETWORK} -d -e MYSQL_ROOT_PASSWORD=funcp --tmpfs /var/lib/mysql/:rw,noexec,nosuid ${IMAGE_MYSQL} >/dev/null
                waitFor mysql-func-${SUFFIX} 3306
                CONTAINERPARAMS="-e typo3DatabaseDriver=${DATABASE_DRIVER} -e typo3DatabaseName=func_test -e typo3DatabaseUsername=root -e typo3DatabaseHost=mysql-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name functional-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${CONTAINERPARAMS} ${IMAGE_PHP} "${COMMAND[@]}"
                SUITE_EXIT_CODE=$?
                ;;
            postgres)
                ${CONTAINER_BIN} run --rm ${CI_PARAMS} --name postgres-func-${SUFFIX} --network ${NETWORK} -d -e POSTGRES_PASSWORD=funcp -e POSTGRES_USER=funcu --tmpfs /var/lib/postgresql/data:rw,noexec,nosuid ${IMAGE_POSTGRES} >/dev/null
                waitFor postgres-func-${SUFFIX} 5432
                CONTAINERPARAMS="-e typo3DatabaseDriver=pdo_pgsql -e typo3DatabaseName=bamboo -e typo3DatabaseUsername=funcu -e typo3DatabaseHost=postgres-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name functional-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${CONTAINERPARAMS} ${IMAGE_PHP} "${COMMAND[@]}"
                SUITE_EXIT_CODE=$?
                ;;
            sqlite)
                # create sqlite tmpfs mount typo3temp/var/tests/functional-sqlite-dbs/ to avoid permission issues
                mkdir -p "${ROOT_DIR}/typo3temp/var/tests/functional-sqlite-dbs/"
                CONTAINERPARAMS="-e typo3DatabaseDriver=pdo_sqlite --tmpfs ${ROOT_DIR}/typo3temp/var/tests/functional-sqlite-dbs/:rw,noexec,nosuid"
                ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name functional-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${CONTAINERPARAMS} ${IMAGE_PHP} "${COMMAND[@]}"
                SUITE_EXIT_CODE=$?
                ;;
        esac
        ;;
    lint)
        COMMAND="php -v | grep '^PHP'; find . -name '*.php' ! -path '*.Build/*' -print0 | xargs -0 -n1 -P4 php -dxdebug.mode=off -l >/dev/null"
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-command-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/bash -c "${COMMAND}"
        SUITE_EXIT_CODE=$?
        ;;
    rector)
        DRY_RUN_OPTIONS=''
        if [ "${DRY_RUN}" -eq 1 ]; then
            DRY_RUN_OPTIONS='--dry-run'
        fi
        COMMAND="php -dxdebug.mode=off .Build/bin/rector process ${DRY_RUN_OPTIONS} --config=Build/rector/rector.php --no-progress-bar --ansi"
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-command-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/sh -c "${COMMAND}"
        SUITE_EXIT_CODE=$?
        ;;
    fractor)
        DRY_RUN_OPTIONS=''
        if [ "${DRY_RUN}" -eq 1 ]; then
            DRY_RUN_OPTIONS='--dry-run'
        fi
        COMMAND="php -dxdebug.mode=off .Build/bin/fractor process ${DRY_RUN_OPTIONS} --config=Build/fractor/fractor.php --ansi"
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name composer-command-${SUFFIX} -e COMPOSER_CACHE_DIR=.cache/composer -e COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION} ${IMAGE_PHP} /bin/sh -c "${COMMAND}"
        SUITE_EXIT_CODE=$?
        ;;
    unit)
        COMMAND=(.Build/bin/phpunit -c Build/phpunit/UnitTests.xml --exclude-group not-${DBMS} ${EXTRA_TEST_OPTIONS} "$@")
        ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name unit-${SUFFIX} ${XDEBUG_MODE} -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" ${IMAGE_PHP} "${COMMAND[@]}"
        SUITE_EXIT_CODE=$?
        ;;
    update)
        # pull typo3/core-testing-*:latest versions of those ones that exist locally
        echo "> pull ghcr.io/typo3/core-testing-*:latest versions of those ones that exist locally"
        ${CONTAINER_BIN} images ghcr.io/typo3/core-testing-*:latest --format "{{.Repository}}:latest" | xargs -I {} ${CONTAINER_BIN} pull {}
        echo ""
        # remove "dangling" typo3/core-testing-* images (those tagged as <none>)
        echo "> remove \"dangling\" ghcr.io/typo3/core-testing-* images (those tagged as <none>)"
        ${CONTAINER_BIN} images --filter "reference=ghcr.io/typo3/core-testing-*" --filter "dangling=true" --format "{{.ID}}" | xargs -I {} ${CONTAINER_BIN} rmi {}
        echo ""
        ;;
    *)
        loadHelp
        echo "Invalid -s option argument ${TEST_SUITE}" >&2
        echo >&2
        echo "${HELP}" >&2
        exit 1
        ;;
esac

cleanUp

# Print summary
echo "" >&2
echo "###########################################################################" >&2
echo "Result of ${TEST_SUITE}" >&2
if [[ ${IS_CI} -eq 1 ]]; then
    echo "Environment: CI" >&2
else
    echo "Environment: local" >&2
fi
echo "PHP: ${PHP_VERSION}" >&2
echo "TYPO3: ${TYPO3_VERSION}" >&2
echo "CONTAINER_BIN: ${CONTAINER_BIN}"
if [[ ${TEST_SUITE} =~ ^functional$ ]]; then
    case "${DBMS}" in
        mariadb|mysql)
            echo "DBMS: ${DBMS}  version ${DBMS_VERSION}  driver ${DATABASE_DRIVER}" >&2
            ;;
        postgres)
            echo "DBMS: ${DBMS}  version ${DBMS_VERSION}  driver pdo_pgsql" >&2
            ;;
        sqlite)
            echo "DBMS: ${DBMS}  driver pdo_sqlite" >&2
            ;;
    esac
fi
if [[ -n ${EXTRA_TEST_OPTIONS} ]]; then
    echo " Note: Using -e is deprecated. Simply add the options at the end of the command."
    echo " Instead of: Build/Scripts/runTests.sh -s ${TEST_SUITE} -e '${EXTRA_TEST_OPTIONS}' $@"
    echo " use:        Build/Scripts/runTests.sh -s ${TEST_SUITE} -- ${EXTRA_TEST_OPTIONS} $@"
fi
if [[ ${SUITE_EXIT_CODE} -eq 0 ]]; then
    echo "SUCCESS" >&2
else
    echo "FAILURE" >&2
fi
echo "###########################################################################" >&2
echo "" >&2

# Exit with code of test suite - This script return non-zero if the executed test failed.
exit $SUITE_EXIT_CODE