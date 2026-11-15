# Agent instructions

Guidance for AI coding agents working in this repository.

The release plan, decisions taken (with reasons), package-by-package review and open questions are in [ROADMAP.md](ROADMAP.md). Read it before proposing structural changes. Day-to-day notes are in `TODO.md`, if present (it is not tracked in git).

## What this is

OCK ("Object Construction Kit") is meant as a replacement for Drupal's plugin system:
- no plugin manager per plugin type: every interface can act as a plugin type;
- any class or static factory method can be a plugin, declared with attributes;
- plugins compose hierarchically: a nested configuration resolves to a tree of composed objects, instantiated from generated PHP code.

It is the successor of the Drupal 7 projects `cfr`, `renderkit`, `entdisp`, `listformat` and `cfrpreset`.

The main product is `packages/object-construction-kit` + `modules/ock` + `modules/renderkit`. The other packages are treated as **internal** for now (see ROADMAP.md).

## Repository layout

- `packages/`: composer packages (vendor `ock/`, namespace `Ock\…`), split into read-only repos by `.github/workflows/split.yaml`.
- `modules/`: Drupal modules (`drupal/…`, namespace `Drupal\…`), split by `split-modules.yaml`.
- `config/`, `web/`: the Drupal site used for development and demos.
- `scripts/`, `.github/`, `.ddev/`: tooling.

Packages, bottom-up by actual dependencies:

```
tier 1 (no ock deps):  helpers, reflection, reflector-aware-attributes, codegen-tools
tier 2:                class-files-iterator, egg
tier 3:                class-discovery → dependency-injection → di-discovery, adaptism → object-construction-kit
dev-only:              testing, drupal-testing
```

- `di-discovery` is obsolete and is being removed. `egg` is expected to be removed too.
- Modules: `ock` (with submodules `ock_preset`, `ock_example`), `renderkit`, `controller_attributes`, `service_discovery`, `themekit`, `ock_demo`.

## Environment

- Development runs in **ddev**. PHP, Composer and tests run inside ddev (`ddev exec …`, `ddev composer …`, `ddev phpunit …`).
- An agent's sandbox may have **no PHP and no usable Docker**. In that case, do not guess results: prepare the change, and ask the maintainer to run the commands and paste back only the relevant output (failures, diffs).
- In [yolobox](https://github.com/finbarr/yolobox), `.yolobox.toml` and `.yolobox.Dockerfile` provide PHP 8.3, 8.4 and 8.5 (`php8.3`, `php8.4`, `php8.5`; `php` is 8.5) and Composer, but no Docker. There you can run the `packages`, `modules-unit` and `modules-kernel` test suites and PhpStan directly, without ddev, e.g. `php8.4 ./vendor/bin/phpunit --testsuite packages`. `modules-functional` and `modules-functional-javascript` still need ddev. `vendor/` is shared with the maintainer's ddev: do not run `composer update` or `composer install` unless asked.
- Do not modify `.yolobox.toml` or `.yolobox.Dockerfile` without being asked: they configure the sandbox itself.
- Drupal core is installed in `web/core` and Symfony in `vendor/`. Check those sources instead of relying on memory about what Drupal or Symfony support.
- GitHub issues and PRs (`ock-php/ock-mono`) are readable without login via `https://api.github.com/repos/ock-php/ock-mono/…`. The issues often contain the reasons behind earlier decisions.

## Commands

All from the repository root, as used in CI (`.github/workflows/`):

```sh
# All tests (packages and modules), as in CI.
ddev exec ./vendor/bin/phpunit --fail-on-incomplete --fail-on-warning --fail-on-skipped --fail-on-risky

# One test suite: packages, modules-unit, modules-kernel, modules-functional, modules-functional-javascript.
ddev exec ./vendor/bin/phpunit --testsuite packages

# PhpStan for all packages.
ddev exec ./scripts/phpstan-all.sh

# Update recorded/snapshot test results, then review the diff in git.
ddev exec UPDATE_TESTS=1 ./vendor/bin/phpunit

# Licensing information (REUSE).
uvx --from 'reuse[charset-normalizer]' reuse lint
```

Many tests compare against recordings (`tests/recordings/`, via `packages/testing` and `packages/drupal-testing`). A changed recording is a change in behaviour: review it, don't just regenerate it.

Keep command output small: filter it (`--filter`, `| tail`, `grep`) and look at the relevant parts. Long output is costly in an agent's context.

## Code conventions

- **`modules/`: strictly Drupal coding standards**, including uppercase `TRUE`, `FALSE`, `NULL`.
- **`packages/`:** this code originated in Drupal, hence the 2-space indentation and much of the Drupal style. Some Drupal rules were dropped on purpose, e.g. lowercase `true`, `false`, `null` are preferred. The package code style is **not final**. Follow the style of the surrounding code, and don't reformat unrelated code.
- `declare(strict_types=1);` in PHP files.
- Class naming: interface `FooInterface`, implementations `Foo_Variant` (underscore before the variant). This applies in both `packages/` and `modules/`. It may be reviewed later, but for now follow it.
- The `IA` suffix means `IteratorAggregate` (e.g. `ClassFilesIA`).
- Tests use `#[CoversClass]` / `#[CoversFunction]`. Coverage metadata is enforced (`requireCoverageMetadata`) in class-files-iterator and controller_attributes; add it in new tests elsewhere too.
- The public API for plugin and module authors should live in `Ock\Ock`. Everything else is internal (`@internal`).

## Licensing

- `packages/` and the monorepo tooling: **MIT** (each package has a `LICENSE` file and `"license": "MIT"`).
- `modules/`: **GPL-2.0-or-later**, as required on drupal.org. Do not add LICENSE files there; drupal.org packaging adds them.
- `config/`, `web/sites/`: GPL-2.0-or-later (derived from Drupal). `.ddev/`: Apache-2.0.
- Path-to-license mapping: `REUSE.toml`. Keep `reuse lint` passing when adding files in new places.

## Working agreements

- **Do not commit.** Leave changes in the working tree; the maintainer reviews and commits. Do not touch unrelated modified files.
- Commit message style, for reference: `Issue 185: PhpStan: Short description.`, or without an issue: `Doc: …`, `CI: …`, `Composer: …`, `Testing: …`, `DevEnv: …`, `Tooling: …`, `Guard: …`, `Fix: …`. `fixup!` commits are used before squashing.
- Changes to `ROADMAP.md` stay uncommitted, like other changes. Some of them may belong to the current feature or PR, others to general plans. When the maintainer asks, stage only the parts that belong to the PR. Interactive `git add -p` is not available, so build a patch with only those hunks and stage it with `git apply --cached`, then show the staged diff (`git diff --cached ROADMAP.md`).
- Record decisions, their reasons, and open questions in `ROADMAP.md`. Use its status markers honestly: **Decided** only when the maintainer has agreed; otherwise **Leaning**, **Open**, or "suggestion".
- Before discarding or restructuring something, look for the history behind it (git log, GitHub issues). Earlier decisions often had reasons that are not obvious from the code.
- Prefer a fresh session per task, with ROADMAP.md as the shared context.
- Avoid "creeping ambition" in the lower layer: changes to lower-level packages should be motivated by a concrete need in ock or renderkit (see ROADMAP.md, "Proposed guiding principle").
