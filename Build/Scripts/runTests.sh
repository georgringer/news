#!/usr/bin/env bash

#
# TYPO3 core test runner based on docker and docker-compose.
#
IMAGE_PREFIX="ghcr.io/typo3/"

# Function to write a .env file in Build/testing-docker
# This is read by docker-compose and vars defined here are
# used in Build/testing-docker/docker-compose.yml
setUpDockerComposeDotEnv() {
    # Delete possibly existing local .env file if exists
    [ -e .env ] && rm .env
    # Set up a new .env file for docker-compose
    {
        echo "COMPOSE_PROJECT_NAME=${PROJECT_NAME}"
        # To prevent access rights of files created by the testing, the docker image later
        # runs with the same user that is currently executing the script. docker-compose can't
        # use $UID directly itself since it is a shell variable and not an env variable, so
        # we have to set it explicitly here.
        echo "HOST_UID=`id -u`"
        # Your local user
        echo "ROOT_DIR=${ROOT_DIR}"
        echo "HOST_USER=${USER}"
        echo "TEST_FILE=${TEST_FILE}"
        echo "TYPO3_VERSION=${TYPO3_VERSION}"
        echo "PHP_XDEBUG_ON=${PHP_XDEBUG_ON}"
        echo "PHP_XDEBUG_PORT=${PHP_XDEBUG_PORT}"
        echo "DOCKER_PHP_IMAGE=${DOCKER_PHP_IMAGE}"
        echo "EXTRA_TEST_OPTIONS=${EXTRA_TEST_OPTIONS}"
        echo "SCRIPT_VERBOSE=${SCRIPT_VERBOSE}"
        echo "CGLCHECK_DRY_RUN=${CGLCHECK_DRY_RUN}"
        echo "DATABASE_DRIVER=${DATABASE_DRIVER}"
        echo "MARIADB_VERSION=${MARIADB_VERSION}"
        echo "MYSQL_VERSION=${MYSQL_VERSION}"
        echo "POSTGRES_VERSION=${POSTGRES_VERSION}"
        echo "USED_XDEBUG_MODES=${USED_XDEBUG_MODES}"
        echo "IMAGE_PREFIX=${IMAGE_PREFIX}"
    } > .env
}

# Options -a and -d depend on each other. The function
# validates input combinations and sets defaults.
handleDbmsAndDriverOptions() {
    case ${DBMS} in
        mysql|mariadb)
            [ -z "${DATABASE_DRIVER}" ] && DATABASE_DRIVER="mysqli"
            if [ "${DATABASE_DRIVER}" != "mysqli" ] && [ "${DATABASE_DRIVER}" != "pdo_mysql" ]; then
                echo "Invalid option -a ${DATABASE_DRIVER} with -d ${DBMS}" >&2
                echo >&2
                echo "call \"./Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
        postgres|sqlite)
            if [ -n "${DATABASE_DRIVER}" ]; then
                echo "Invalid option -a ${DATABASE_DRIVER} with -d ${DBMS}" >&2
                echo >&2
                echo "call \"./Build/Scripts/runTests.sh -h\" to display help and valid options" >&2
                exit 1
            fi
            ;;
    esac
}

# Load help text into $HELP
read -r -d '' HELP <<EOF
georgringer/news test runner. Execute unit test suite and some other details.
Also used by github for test execution.

Recommended docker version is >=20.10 for xdebug break pointing to work reliably, and
a recent docker-compose (tested >=1.21.2) is needed.

Usage: $0 [options] [file]

No arguments: Run all unit tests with PHP 7.4

Options:
    -s <...>
        Specifies which test suite to run
            - cgl: cgl test and fix all php files
            - clean: clean up build and testing related files
            - composer: Execute "composer" command, using -e for command arguments pass-through, ex. -e "ci:php:stan"
            - composerInstall: "composer update", handy if host has no PHP
            - composerInstallLowest: "composer update", handy if host has no PHP
            - composerInstallHighest: "composer update", handy if host has no PHP
            - functional: functional tests
            - lint: PHP linting
            - unit: PHP unit tests

    -a <mysqli|pdo_mysql>
        Only with -s acceptance,functional
        Specifies to use another driver, following combinations are available:
            - mysql
                - mysqli (default)
                - pdo_mysql
            - mariadb
                - mysqli (default)
                - pdo_mysql

    -d <sqlite|mariadb|mysql|postgres>
        Only with -s acceptance,functional
        Specifies on which DBMS tests are performed
            - sqlite: use sqlite
            - mariadb: (default) use mariadb
            - mysql: use mysql
            - postgres: use postgres

    -i <10.2|10.3|10.4|10.5|10.6|10.7>
        Only with -d mariadb
        Specifies on which version of mariadb tests are performed
            - 10.2 (default)
            - 10.3
            - 10.4
            - 10.5
            - 10.6
            - 10.7

    -j <5.5|5.6|5.7|8.0>
        Only with -d mysql
        Specifies on which version of mysql tests are performed
            - 5.5 (default)
            - 5.6
            - 5.7
            - 8.0

    -k <10|11|12|13|14>
        Only with -d postgres
        Specifies on which version of postgres tests are performed
            - 10 (default)
            - 11
            - 12
            - 13
            - 14

    -p <7.4|8.0|8.1|8.2|8.3>
        Specifies the PHP minor version to be used
            - 7.4 (default): use PHP 7.4
            - 8.0: use PHP 8.0
            - 8.1: use PHP 8.1
            - 8.2: use PHP 8.2
            - 8.3: use PHP 8.3

    -t <11|12>
        Only with -s composerUpdate
        Specifies the TYPO3 core major version to be used
            - 11 (default): use TYPO3 core v11
            - 12: use TYPO3 core v12

    -e "<composer, phpunit or codeception options>"
        Only with -s functional|unit|composer
        Additional options to send to phpunit (unit & functional tests) or codeception (acceptance
        tests). For phpunit, options starting with "--" must be added after options starting with "-".
        Example -e "-v --filter canRetrieveValueWithGP" to enable verbose output AND filter tests
        named "canRetrieveValueWithGP"

    -x
        Only with -s functional|unit
        Send information to host instance for test or system under test break points. This is especially
        useful if a local PhpStorm instance is listening on default xdebug port 9003. A different port
        can be selected with -y

    -y <port>
        Send xdebug information to a different port than default 9003 if an IDE like PhpStorm
        is not listening on default port.

    -z <xdebug modes>
        Only with -x and -s functional|unit|acceptance
        This sets the used xdebug modes. Defaults to 'debug,develop'

    -n
        Only with -s cgl
        Activate dry-run in CGL check that does not actively change files and only prints broken ones.

    -u
        Update existing ${IMAGE_PREFIX}core-testing-*:latest docker images. Maintenance call to docker pull latest
        versions of the main php images. The images are updated once in a while and only the youngest
        ones are supported by core testing. Use this if weird test errors occur. Also removes obsolete
        image versions of ${IMAGE_PREFIX}core-testing-*.

    -v
        Enable verbose script output. Shows variables and docker commands.

    -h
        Show this help.

Examples:
    # Run unit tests using PHP 7.4
    ./Build/Scripts/runTests.sh -s unit
EOF

# Test if docker-compose exists, else exit out with error
if ! type "docker-compose" > /dev/null; then
  echo "This script relies on docker and docker-compose. Please install" >&2
  exit 1
fi

# Go to the directory this script is located, so everything else is relative
# to this dir, no matter from where this script is called.
THIS_SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
cd "$THIS_SCRIPT_DIR" || exit 1

# Go to directory that contains the local docker-compose.yml file
cd ../testing-docker || exit 1

# Option defaults
if ! command -v realpath &> /dev/null; then
  echo "This script works best with realpath installed" >&2
  ROOT_DIR="${PWD}/../../"
else
  ROOT_DIR=`realpath ${PWD}/../../`
fi
TEST_SUITE=""
DBMS="mariadb"
PHP_VERSION="7.4"
TYPO3_VERSION="11"
PHP_XDEBUG_ON=0
PHP_XDEBUG_PORT=9003
EXTRA_TEST_OPTIONS=""
SCRIPT_VERBOSE=0
CGLCHECK_DRY_RUN=""
DATABASE_DRIVER=""
MARIADB_VERSION="10.2"
MYSQL_VERSION="5.5"
POSTGRES_VERSION="10"
USED_XDEBUG_MODES="debug,develop"
#@todo the $$ would add the current process id to the name, keeping as plan b
#PROJECT_NAME="runTests-$(basename $(dirname $ROOT_DIR))-$(basename $ROOT_DIR)-$$"
PROJECT_NAME="runTests-$(basename $(dirname $ROOT_DIR))-$(basename $ROOT_DIR)"
PROJECT_NAME="${PROJECT_NAME//[[:blank:]]/}"
echo $PROJECT_NAME

# Option parsing
# Reset in case getopts has been used previously in the shell
OPTIND=1
# Array for invalid options
INVALID_OPTIONS=();
# Simple option parsing based on getopts (! not getopt)
while getopts ":s:a:d:i:j:k:p:t:e:xy:z:nhuv" OPT; do
    case ${OPT} in
        s)
            TEST_SUITE=${OPTARG}
            ;;
        a)
            DATABASE_DRIVER=${OPTARG}
            ;;
        d)
            DBMS=${OPTARG}
            ;;
        i)
            MARIADB_VERSION=${OPTARG}
            if ! [[ ${MARIADB_VERSION} =~ ^(10.2|10.3|10.4|10.5|10.6|10.7)$ ]]; then
                INVALID_OPTIONS+=("${OPTARG}")
            fi
            ;;
        j)
            MYSQL_VERSION=${OPTARG}
            if ! [[ ${MYSQL_VERSION} =~ ^(5.5|5.6|5.7|8.0)$ ]]; then
                INVALID_OPTIONS+=("${OPTARG}")
            fi
            ;;
        k)
            POSTGRES_VERSION=${OPTARG}
            if ! [[ ${POSTGRES_VERSION} =~ ^(10|11|12|13|14)$ ]]; then
                INVALID_OPTIONS+=("${OPTARG}")
            fi
            ;;
        p)
            PHP_VERSION=${OPTARG}
            if ! [[ ${PHP_VERSION} =~ ^(7.4|8.0|8.1|8.2|8.3)$ ]]; then
                INVALID_OPTIONS+=("p ${OPTARG}")
            fi
            ;;
        t)
            TYPO3_VERSION=${OPTARG}
            if ! [[ ${TYPO3_VERSION} =~ ^(11|12)$ ]]; then
                INVALID_OPTIONS+=("p ${OPTARG}")
            fi
            ;;
        e)
            EXTRA_TEST_OPTIONS=${OPTARG}
            ;;
        x)
            PHP_XDEBUG_ON=1
            ;;
        y)
            PHP_XDEBUG_PORT=${OPTARG}
            ;;
        z)
            USED_XDEBUG_MODES=${OPTARG}
            ;;
        h)
            echo "${HELP}"
            exit 0
            ;;
        n)
            CGLCHECK_DRY_RUN="-n"
            ;;
        u)
            TEST_SUITE=update
            ;;
        v)
            SCRIPT_VERBOSE=1
            ;;
        \?)
            INVALID_OPTIONS+=(${OPTARG})
            ;;
        :)
            INVALID_OPTIONS+=(${OPTARG})
            ;;
    esac
done

# Exit on invalid options
if [ ${#INVALID_OPTIONS[@]} -ne 0 ]; then
    echo "Invalid option(s):" >&2
    for I in "${INVALID_OPTIONS[@]}"; do
        echo "-"${I} >&2
    done
    echo >&2
    echo "${HELP}" >&2
    exit 1
fi

# Move "7.2" to "php72", the latter is the docker container name
DOCKER_PHP_IMAGE=`echo "php${PHP_VERSION}" | sed -e 's/\.//'`

# Set $1 to first mass argument, this is the optional test file or test directory to execute
shift $((OPTIND - 1))
TEST_FILE=${1}

if [ ${SCRIPT_VERBOSE} -eq 1 ]; then
    set -x
fi

if [ -z ${TEST_SUITE} ]; then
    echo "${HELP}"
    exit 0
fi

# Suite execution
case ${TEST_SUITE} in
    cgl)
        # Active dry-run for cgl needs not "-n" but specific options
        if [[ ! -z ${CGLCHECK_DRY_RUN} ]]; then
            CGLCHECK_DRY_RUN="--dry-run --diff"
        fi
        setUpDockerComposeDotEnv
        docker-compose run cgl
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    clean)
        rm -rf \
          ../../var/ \
          ../../.cache \
          ../../composer.lock \
          ../../.Build/ \
          ../../Tests/Acceptance/Support/_generated/ \
          ../../composer.json.testing
        ;;
    composer)
        setUpDockerComposeDotEnv
        docker-compose run composer
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    composerInstall)
        setUpDockerComposeDotEnv
        cp ../../composer.json ../../composer.json.orig
        if [ -f "../../composer.json.testing" ]; then
            cp ../../composer.json ../../composer.json.orig
        fi
        docker-compose run composer_install
        cp ../../composer.json ../../composer.json.testing
        mv ../../composer.json.orig ../../composer.json
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    composerInstallLowest)
        setUpDockerComposeDotEnv
        cp ../../composer.json ../../composer.json.orig
        if [ -f "../../composer.json.testing" ]; then
            cp ../../composer.json ../../composer.json.orig
        fi
        docker-compose run composer_install_lowest
        cp ../../composer.json ../../composer.json.testing
        mv ../../composer.json.orig ../../composer.json
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    composerInstallHighest)
        setUpDockerComposeDotEnv
        cp ../../composer.json ../../composer.json.orig
        if [ -f "../../composer.json.testing" ]; then
            cp ../../composer.json ../../composer.json.orig
        fi
        docker-compose run composer_install_highest
        cp ../../composer.json ../../composer.json.testing
        mv ../../composer.json.orig ../../composer.json
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    coveralls)
        setUpDockerComposeDotEnv
        docker-compose run coveralls
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    functional)
        handleDbmsAndDriverOptions
        setUpDockerComposeDotEnv
        case ${DBMS} in
            mariadb)
                echo "Using driver: ${DATABASE_DRIVER}"
                docker-compose run functional_mariadb
                SUITE_EXIT_CODE=$?
                ;;
            mysql)
                echo "Using driver: ${DATABASE_DRIVER}"
                docker-compose run functional_mysql
                SUITE_EXIT_CODE=$?
                ;;
            postgres)
                docker-compose run functional_postgres
                SUITE_EXIT_CODE=$?
                ;;
            sqlite)
                # sqlite has a tmpfs as Web/typo3temp/var/tests/functional-sqlite-dbs/
                # Since docker is executed as root (yay!), the path to this dir is owned by
                # root if docker creates it. Thank you, docker. We create the path beforehand
                # to avoid permission issues.
                mkdir -p ${ROOT_DIR}/Web/typo3temp/var/tests/functional-sqlite-dbs/
                docker-compose run functional_sqlite
                SUITE_EXIT_CODE=$?
                ;;
            *)
                echo "Invalid -d option argument ${DBMS}" >&2
                echo >&2
                echo "${HELP}" >&2
                exit 1
        esac
        docker-compose down
        ;;
    lint)
        setUpDockerComposeDotEnv
        docker-compose run lint
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    phpstan)
        setUpDockerComposeDotEnv
        docker-compose run phpstan
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    phpstanGenerateBaseline)
        setUpDockerComposeDotEnv
        docker-compose run phpstan_generate_baseline
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    unit)
        setUpDockerComposeDotEnv
        docker-compose run unit
        SUITE_EXIT_CODE=$?
        docker-compose down
        ;;
    update)
        # pull ${IMAGE_PREFIX}core-testing-*:latest versions of those ones that exist locally
        docker images ${IMAGE_PREFIX}core-testing-*:latest --format "{{.Repository}}:latest" | xargs -I {} docker pull {}
        # remove "dangling" ${IMAGE_PREFIX}core-testing-* images (those tagged as <none>)
        docker images ${IMAGE_PREFIX}core-testing-* --filter "dangling=true" --format "{{.ID}}" | xargs -I {} docker rmi {}
        ;;
    *)
        echo "Invalid -s option argument ${TEST_SUITE}" >&2
        echo >&2
        echo "${HELP}" >&2
        exit 1
esac

exit $SUITE_EXIT_CODE
