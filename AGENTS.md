# Repository Guidelines

Guidance for coding agents working on EXT:news. Personal or machine-specific
preferences do not belong here — put those in an untracked `CLAUDE.local.md`
(already gitignored).

## Context

- One Composer package, `georgringer/news`, PSR-4 `GeorgRinger\News\` →
  `Classes/` and `GeorgRinger\News\Tests\` → `Tests/`.
- **One branch serves TYPO3 13.4 LTS and 14**, on PHP 8.2–8.5. Every change
  has to work on both cores; `.github/workflows/core13.yml` and `core14.yml`
  define the matrix that decides. Where the cores differ, switch on
  `(new Typo3Version())->getMajorVersion()` — see `Classes/Utility/Page.php`
  or `Classes/ViewHelpers/LinkViewHelper.php`.
- Issues and code review both happen on GitHub,
  <https://github.com/georgringer/news>. There is no Gerrit and no Forge here;
  TYPO3 Core conventions around `Change-Id`, `Releases:` trailers or
  per-change RST files do **not** apply.
- Documentation sources live in `Documentation/`, rendered at
  <https://docs.typo3.org/p/georgringer/news/main/en-us/>.
- Contribution walkthrough for humans: `.github/CONTRIBUTING.md`.

## Working mode

- When a bug is reported or reproduced, **do not start by fixing it**. First
  write a test that reproduces it, then fix and prove it with that test
  passing. The only exception is a purely mechanical fix with no testable
  behaviour, such as a typo in a label, comment or documentation.
- Only add code comments when they add meaning. Anything the code already says
  plainly needs no comment — do not restate the method name, the parameters or
  the obvious control flow. Comment the surprising part: why a workaround
  exists, which edge case a branch guards, what breaks without it.
- Do not break public API and do not drop TYPO3 13 support in passing.

## Project structure

- `Classes/` — `Controller/` (Extbase; `NewsController` is the frontend entry
  point, `AdministrationController` the backend module),
  `Domain/{Model,Repository,Service}` with the demand objects in
  `Domain/Model/Dto/`, `ViewHelpers/`, `Backend/`, `Command/`,
  `DataProcessing/`, `Event/` (plus `Event/Listener/`), `Seo/`, `Service/`,
  `Updates/`, `Utility/`, and the legacy `Hooks/` and `Xclass/`.
- `Configuration/` — `TCA/` and `TCA/Overrides/`, `Sets/` (site sets: `News`,
  `RecordLinks`, `Sitemap`, `Twb4`, `Twb5`), `TypoScript/`, `TSconfig/`,
  `FlexForms/`, `Backend/`, `Extbase/`, `Services.yaml` and `Services.php`.
- `Resources/Private/{Templates,Partials,Layouts,Language,Backend}`,
  `Resources/Public/`.
- `Tests/{Unit,Functional}` mirroring the `Classes/` namespace; fixtures in
  `Tests/Functional/Fixtures/`.
- `Build/` — `Scripts/runTests.sh` (the single test entry point), `phpunit/`,
  `php-cs-fixer/`, `rector/`, `fractor/`.
- Root: `ext_emconf.php`, `ext_localconf.php`, `ext_tables.php`,
  `ext_tables.sql`, `ext_conf_template.txt`.
- `.Build/` holds Composer's vendor and web dir (`vendor-dir: .Build/vendor`),
  generated and gitignored — never edit.

## Commands

Everything runs through `Build/Scripts/runTests.sh`, which starts a container
with the requested PHP version and database. Do not invoke `phpunit`,
`php-cs-fixer`, `rector` or `fractor` directly — the wrapper supplies the
environment. `-h` is the authoritative, always-current list of suites.

Three things that are easy to get wrong:

- **Prefix local runs with `CI=true`.** Otherwise the script requests a TTY and
  aborts with `cannot attach stdin to a TTY-enabled container`.
- **`-s composerInstall` ignores `-t` and installs TYPO3 13.** Use
  `composerInstallHighest` or `composerInstallLowest`, which is what CI does —
  otherwise you silently test the wrong core and, for example, never see a v14
  deprecation.
- The container engine defaults to **docker** here (`-b podman` to switch),
  the opposite of the TYPO3 Core script.

```bash
# install a test system: pick core version and dependency resolution
CI=true Build/Scripts/runTests.sh -t 14 -p 8.3 -s composerInstallHighest
CI=true Build/Scripts/runTests.sh -t 13 -p 8.2 -s composerInstallLowest

# tests
CI=true Build/Scripts/runTests.sh -p 8.3 -s lint
CI=true Build/Scripts/runTests.sh -p 8.3 -s unit
CI=true Build/Scripts/runTests.sh -p 8.3 -s unit -- --filter someTestMethod
CI=true Build/Scripts/runTests.sh -p 8.3 -s functional              # sqlite
CI=true Build/Scripts/runTests.sh -p 8.3 -d mariadb -a mysqli -s functional
CI=true Build/Scripts/runTests.sh -p 8.3 -s functional -- \
    Tests/Functional/Repository/NewsRepositoryTest.php

# style and automated migrations (-n is the dry-run CI checks)
PHP_CS_FIXER_IGNORE_ENV=1 CI=true Build/Scripts/runTests.sh -p 8.3 -s cgl -n
CI=true Build/Scripts/runTests.sh -p 8.3 -s rector -n
CI=true Build/Scripts/runTests.sh -p 8.3 -s fractor -n

# documentation
make docs        # renders Documentation/ to Documentation-GENERATED-temp/
```

Functional tests default to SQLite, by far the fastest feedback loop; only
switch DBMS when the change touches SQL specifics. `-x` forwards xdebug to a
listening IDE (port 9003, `-y` for another port), and `-u` updates the
`typo3/core-testing-*` images — the first thing to try when a suite fails in
ways the code cannot explain.

`composer cs` and `composer csfix` are host-side php-cs-fixer shortcuts for
when a local PHP is available.

## Architecture

- **Extbase MVC.** Plugins are configured per site set and FlexForm; the
  controllers build a `NewsDemand` object from settings, hand it to
  `Domain/Repository/NewsRepository`, and render Fluid templates. Overriding
  points along that path are events, not method overrides.
- **Dependency injection.** `Configuration/Services.yaml` autowires
  `Classes/*` (domain models excluded). `Configuration/Services.php` adds
  autoconfiguration tags (`news.ItemsProcFunc`, `news.PageLayoutView`,
  `news.NewsSlugUpdater`) and `SingletonPass` compiler passes for the legacy
  hook objects. Prefer constructor injection; never inject request-scoped
  objects such as `ContentObjectRenderer` or `Context` into ViewHelpers.
- **PSR-14 events.** New extension points go into `Classes/Event/`, listeners
  into `Classes/Event/Listener/`. `Classes/Hooks/` and `Classes/Xclass/` are
  legacy and should not grow.
- **Site sets.** `Configuration/Sets/*` is the modern configuration entry
  point (settings such as `news.pages.detail`) and is preferred over new
  TypoScript constants.
- **Templates.** `Resources/Private/Templates/` is the default set,
  `Resources/Private/Templates/Styles/Twb/` and `.../Styles/Twb5/` are the
  Bootstrap variants; the `Twb4` and `Twb5` sets point at them through
  `news.view.*RootPath`. A template change usually has to be applied to the
  default set and the Bootstrap variants alike, and the functional frontend
  tests render both the default and the Twb5 set.

## Coding style

- **Everything in the code base is written in English**: class, method and
  variable names, code comments, docblocks, commit messages, changelog and
  documentation. Never leave German (or any other language) in source files —
  only the translated `*.xlf` files hold non-English text.
- PSR-12 via `typo3/coding-standards`, configured in
  `Build/php-cs-fixer/php-cs-fixer.php`. `-s cgl` is the authority; CI runs it
  as a dry-run on PHP 8.3.
- Every PHP file carries the GPL file header comment
  (`This file is part of the "news" Extension for TYPO3 CMS.`).
- `declare(strict_types=1);` is **not** applied consistently (roughly a third
  of `Classes/`). Add it to new files, but do not retrofit it into existing
  files as a drive-by — it changes runtime behaviour.
- `.editorconfig` is authoritative for formatting: RST and Markdown indent with
  4 spaces and wrap at 80 columns, XML uses tabs.
- Labels live in `Resources/Private/Language/locallang*.xlf`. Only the English
  source files are edited here — translations are maintained on Crowdin
  (`crowdin.yml`) and must not be hand-edited.

## Testing

- Unit tests extend the testing-framework base cases (`UnitTestCase` or
  `BaseTestCase`); functional tests extend `FunctionalTestCase` and resolve
  services with `$this->get(SomeClass::class)`. File names end in `Test.php`.
- Use PHPUnit attributes (`#[Test]`, `#[DataProvider]`), not docblock
  annotations.
- `Build/phpunit/FunctionalTests.xml` sets `failOnDeprecation`, `failOnNotice`,
  `failOnRisky` and `failOnWarning`. **A triggered TYPO3 deprecation fails the
  build even when every assertion passes** — the run prints
  `OK, but there were issues!` and still exits non-zero. Use
  `#[IgnoreDeprecations]` only for a deprecation that is deliberate and
  explained in a comment, such as a documented v13 BC path.
- Deprecations only appear against the development core, so reproduce reports
  like these with `-t 14 -s composerInstallHighest`, never with plain
  `-s composerInstall`.
- DB fixtures are CSV files loaded with `importCSVDataSet()`. These paths do
  not resolve `EXT:` prefixes — use `__DIR__`-relative paths.
- Prefer small, focused tests, and filter with `-- --filter <TestName>`.

## Changelog and documentation

- The changelog is **per release**, not per change:
  `Documentation/Misc/Changelog/<major>-<minor>-<patch>.rst` lists every commit
  since the previous tag. Produce that list with

  ```bash
  git log $(git describe --tags --abbrev=0)..HEAD --abbrev-commit \
      --pretty='%ad %s (Commit %h by %an)' --date=short
  ```

  and register the new file at the top of the toctree in
  `Documentation/Misc/Changelog/Index.rst`.
- Breaking changes and new features get a short prose section in that same
  release file, above the generated commit list.
- User-facing behaviour also belongs in the matching chapter under
  `Documentation/` (`Reference/`, `UsersManual/`, `Administration/`, …).

## Commits and pull requests

**Only create or modify commits when explicitly asked.**

- Subject tags: `[BUGFIX]`, `[FEATURE]`, `[TASK]`, `[DOC]`. Imperative, concise
  subject; the body describes the behaviour without the patch, why that is a
  problem, and how the patch fixes it.
- Reference the GitHub issue in the footer as `Resolves: #123`.
- Work on a topic branch off `main` and open a pull request; do not commit to
  `main` directly.
- Keep commits as logical units — an unrelated cleanup belongs in its own
  commit, even when it touches the same file.
- Do not credit tooling or assistants in commit messages.
- Before pushing, `lint`, `unit`, `functional` and `cgl -n` should be green,
  and on both TYPO3 13 and 14 whenever the change touches version-sensitive
  code.

## Security

- Never commit secrets or credentials.
- Report potential security issues privately to the TYPO3 Security Team
  (<security@typo3.org>) instead of opening a public GitHub issue.
