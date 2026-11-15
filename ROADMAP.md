# Roadmap

This file records the release strategy, the decisions taken so far (with their reasons), and open questions to come back to.

For day-to-day tasks, see [TODO.md](TODO.md).

Status markers:
- **Decided**: agreed, act on it.
- **Leaning**: preferred direction, not final.
- **Open**: needs a decision.

## Goal

Release packages and modules that offer clear value to the PHP/Drupal ecosystem, big enough to justify the dependency buy-in.

The main product is **object-construction-kit + ock + renderkit**: a replacement for Drupal core's plugin system.
- No plugin manager per plugin type: every interface can act as a plugin type.
- Any class or static factory method can be a plugin, declared with attributes.
- Plugins compose hierarchically: a nested configuration resolves to a tree of composed objects.
- Objects are instantiated from generated PHP code, for performance.

History: this is the successor of the Drupal 7 projects `cfr` (cfrapi, cfrplugin, …), `renderkit`, `entdisp`, `listformat`, `cfrpreset` and `cfrblock`. They arrived late in the D7 lifecycle and never took off.

## Proposed guiding principle

From the "creeping ambition" discussion: most lower-level parts became more general than their only consumer needs. Examples:
- adaptism adapts anything to anything, but only formulas are adapted;
- class-discovery has a general fact pipeline, but only one consumer;
- egg, DID's `ValueDefinition` and Symfony's `Definition` are three versions of "value recipes";
- packages were split for standalone users who don't exist yet;
- class-files-iterator was polished to 100% coverage while the product had no site-builder integration.

Related terms: scope creep, second-system effect (OCK is the second system after cfr), inner-platform effect, YAGNI, yak shaving, gold plating.

Proposed rule (not yet confirmed): **every change to a lower-level package should be motivated by a concrete need in ock or renderkit, and nothing in the lower layer is polished for its own sake until the product works end to end.** Generalize when there are three real uses ("rule of three"), and work top-down, from a use case through a thin vertical slice.

## Release strategy

### Decided: release the high-level product first, treat everything else as internal

- Release ock + renderkit first. The lower-level packages are internal for now.
- Reason: releasing low-level packages first would freeze the parts we are least sure about. Those packages would also have to compete with established alternatives (Symfony components, Drupal core).
- The real BC surface is **what plugin authors write**: attributes, interfaces, base classes. The number of packages matters less.
- Namespaces matter more than package boundaries. Moving classes between packages, or merging packages, is not a BC break for users as long as the namespaces stay. Renaming namespaces or public attributes is.

### Decided: how to keep internals internal

- Mark non-public code `@internal`.
- PHPStan decides "internal" by **root namespace**, not by package. `Ock\*` using `Ock\*` internals is not reported. `Drupal\ock` / `Drupal\renderkit` using them is.
- Our own internal usages in the monorepo can be ignored. If the modules get CI on drupal.org (which runs PHPStan), add scoped ignore rules in each module's `phpstan.neon`, and reduce those usages over time.
- Release all packages in lockstep from the monorepo, with exact version constraints between them.
- Use 0.x / alpha releases until the authoring API is settled.

How to mark a package as internal (Composer has no "internal" flag):
- **`description` in composer.json and a README banner.** Phrase it as part of a maintained product, not as "unstable" (that alarms corporate reviewers), e.g.: "Implementation part of the OCK ecosystem, released together with drupal/ock. Its PHP API is not intended for direct use and is not covered by the BC promise."
- **`@internal` on the classes.** PHPStan reports usage from other root namespaces.
- Optionally **`"extra": {"ock": {"internal": true}}`**, for our own CI checks.
- **One documented BC policy** in the main README or docs, listing which packages and symbols are covered.
- Avoid marking packages `abandoned`, and avoid a big rename into `…\Internal\` namespaces.

Versioning: keep **lockstep versions** for all packages, with the BC promise defined per package and symbol in documentation. Symfony does the same: all components share a version, and experimental components and `@internal` classes are excluded from the promise. Independent version lines (internal 0.x, mature 1.x) are possible, but need per-package tags and more bookkeeping.

Tags: `.github/workflows/split.yaml` currently pushes tags only for packages marked `release: true` (class-files-iterator, helpers, reflector-aware-attributes). Once object-construction-kit is released, **all its dependencies must be tagged too**, because they are required with `self.version`. So "internal" has to be expressed by the conventions above, not by withholding tags.

Will an internal dependency deter users? The risk is low: Composer does not show it, and internal-flavoured dependencies are common (`symfony/polyfill-*`, `symfony/deprecation-contracts`). It matters mostly for formal reviews (hence the wording) and for packages that look inactive (lockstep releases keep them fresh). Merging packages later is invisible to users: the merged package declares the old ones in Composer's `replace` section.

### Decided: criteria for promoting a lower-level package to a standalone release

Splitting packages early was not wrong. It just bought less than hoped, because the boundaries were drawn before the design settled. Being on Packagist (required for Composer) is not the same as being promoted.

A package earns an independent release when:
- it solves a known, generic problem that people already look for;
- it has, or plausibly will have, consumers outside OCK;
- its API has stopped changing for a while;
- it is small enough that depending on it is a non-decision.

Closest candidates: `reflector-aware-attributes`, `class-files-iterator`, maybe `controller_attributes`.

### Leaning: reduce the number of packages

`composer require drupal/renderkit` currently pulls in 15 of our own packages (11 `ock/*` + `drupal/ock`, `controller_attributes`, `service_discovery`, `themekit`). That affects first impressions, dependency reviews and maintenance. Target: about 4–6.

| Package | Plan |
|---|---|
| di-discovery | Remove (decided, see below) |
| egg | Remove if possible (see below) |
| adaptism | Merge into OCK (leaning, see below) |
| helpers, reflection, class-files-iterator, class-discovery | Candidates to merge into one discovery package, or into OCK |
| service_discovery, controller_attributes, themekit | Check for redundancy with Drupal core (open). At least, renderkit should not need to require them |
| codegen-tools, dependency-injection, reflector-aware-attributes | Keep for now |
| testing, drupal-testing | Dev-only, does not count |

**Timing:** collapse packages **after the first alpha, but before 1.0**. The alpha targets early adopters. A stable release aimed at companies should come with a small number of packages (see corporate allowlists below).

### Corporate dependency allowlists

Many companies only allow approved Composer packages, typically via a private proxy (Private Packagist, Artifactory, Nexus, Satis), often combined with scanners (Snyk, Mend, Black Duck). Practices vary; this is the typical picture.
- Approval is usually **per package**. Sometimes it is per vendor (`symfony/*`, `drupal/*`), but that trust is earned by well-known vendors.
- Typical criteria: license (often automated; GPL may need extra approval), number of maintainers (bus factor), activity and adoption, and security process (policy, advisories, security team coverage).

For `drupal/renderkit`, today that means about 15 approval requests, each for a single-maintainer, low-download package, most without a description.

What helps, in rough order of impact:
1. Fewer packages before 1.0 (see timing above).
2. Ship the core inside the drupal.org project (see the packaging question). `drupal/*` is a vendor that some organisations already allow, and security team coverage is a standard criterion. This could reduce about 15 approvals to 2, plus any standalone libraries that remain.
3. Clear, consistent licensing (see "Licensing" below; packages are now all MIT).
4. Vendor-level trust signals: the same maintainers on every package, a `SECURITY.md` with a reporting address, consistent descriptions, release notes. A second maintainer would help the bus-factor check more than any technical change.
5. Lockstep releases, so every internal package shows recent activity.

### Licensing

**Decided:** all packages in `packages/` are MIT; the Drupal modules in `modules/` are GPL-2.0-or-later (required by drupal.org). Done: `license` fixed to `MIT` in object-construction-kit, dependency-injection and di-discovery; MIT LICENSE files added to dependency-injection, di-discovery, testing and drupal-testing.

Remaining:
- If `Ock\Ock` moves into the drupal.org project, decide between options 2 and 3 below, and add a contribution note.
- Modules: all declare `GPL-2.0-or-later` (the drupal.org standard). drupal.org packaging adds `LICENSE.txt` to releases, and maintainers are asked not to commit their own. Nothing to do, except checking that the split module repos don't need a LICENSE for anything outside drupal.org.
- Root of the monorepo: MIT (depending on GPL packages does not make the tooling GPL). Done: root `LICENSE`, a "Licensing" section in `README.md`, and `REUSE.toml` + `LICENSES/`. `reuse lint` passes (REUSE 3.3). Exceptions: `modules/`, and `config/` + `web/sites/` (derived from Drupal), are GPL-2.0-or-later; `.ddev/**` is Apache-2.0 as a whole, like ddev and its official add-ons. This also covers `.ddev/commands/web/here` (a snippet from a ddev issue comment). When installing a third-party ddev add-on, check that it is Apache-2.0 too.

State before the decision (kept for reference):

| Package | `license` in composer.json | LICENSE file |
|---|---|---|
| **object-construction-kit** | `GPL-2.0-or-later` | **MIT text** |
| dependency-injection, di-discovery | `GPL-2.0-or-later` | none |
| testing, drupal-testing | `MIT` | none |
| all modules | `GPL-2.0-or-later` | none (normal for drupal.org, which adds it when packaging) |
| other packages | `MIT` | MIT |

Problems:
- **object-construction-kit's declared license contradicts its LICENSE file.** Compliance scanners treat this as unclear licensing, often an automatic block. This must be resolved before any release.
- **MIT packages depend on GPL packages:** adaptism and egg (MIT) use dependency-injection (GPL). Inside Drupal this does not matter, since everything is GPL. For a standalone audience, a GPL package low in the stack makes the MIT label above it meaningless, and some corporate policies flag GPL.
- Some packages have no LICENSE file.

To decide (the copyright holder's decision): one license strategy, e.g. MIT for all `ock/*` libraries and GPL-2.0-or-later for the Drupal modules (required on drupal.org), with no MIT package depending on a GPL package.

#### Can `Ock\Ock` be GPL inside the Drupal module now, and MIT when split out later?

General open-source practice, not legal advice:
- The copyright holder decides the license, and can relicense new releases (or offer several licenses) at any time. Copies already released under GPL stay available under GPL.
- **The catch is other contributors.** Each contributor owns the copyright of their contribution. Code contributed to a drupal.org project is GPL-2.0-or-later, and drupal.org has no contributor agreement. Relicensing would need each contributor's permission, or a rewrite of their parts.
- Current state: all 2,728 commits in the monorepo (including merged-in histories) are by the maintainer, with no credited third-party patches. Authorship is not watertight proof, but the license can most likely still be chosen freely.

Which code must be GPL:
- The Drupal modules use Drupal APIs and count as derivative works of Drupal: **GPL is required**.
- `Ock\Ock` without Drupal imports (to be enforced by PHPStan or deptrac) is not derived from Drupal: **MIT is possible**, wherever the files live. Keeping the core Drupal-free also keeps the license choice open.
- An MIT core must not depend on GPL packages. Today object-construction-kit uses dependency-injection (declared GPL).

Options:
1. **`Ock\Ock` MIT from the start, as a separate package outside drupal.org.** No relicensing issue ever arises. The allowlist and security-coverage benefits of shipping inside drupal.org are lost.
2. **Inside the drupal.org project as GPL, relicensed to MIT when split out.** Works while the maintainer is the sole author; each outside contribution makes it harder. Mitigation: a note in the issue/MR template ("by contributing to `lib/`, you agree it may also be released under MIT").
3. **Dual license from the start: `MIT OR GPL-2.0-or-later`** (SPDX expression in `composer.json`). Used under GPL on drupal.org, available under MIT elsewhere. Contributions are still the weak point unless contributors agree to both licenses. Check whether drupal.org's repository policy ("everything in drupal.org git is GPL-2.0-or-later") accepts dual-licensed files.

Suggestion (not yet decided): if `Ock\Ock` might ever become independent, choose MIT for the core **now**, while there is a single author (option 1 or 3). If the core goes to drupal.org, add a contribution note either way.

## Architecture decisions

### Decided: Drupal module layout

- The D7 `entdisp`, `listformat` and `cfrblock` integrations become part of the **renderkit** module.
- The D7 `cfrpreset` functionality is part of the **ock** module (`ock_preset`).

### Decided: remove di-discovery (DID)

It is obsolete and only kept for leftover usages. Outside its own package, the modules use almost only attributes:
- `#[GetService]` (31×), `#[CallService]`, `#[CallServiceMethod]`, `#[GetContainer]`, … (about 8×)
- `ContainerToValueException` (1×, in `service_discovery`)

The `ValueDefinition_*` machinery is not used outside DID.

Plan: let OCK's parameter resolution understand Symfony's `#[Autowire]` (service ID, or parameter type as the service ID) instead of an ock-specific attribute. Replace the rarer `#[CallService*]` with a plain service plus a method call in the factory.

### Leaning: explicit service registration, configured by Symfony

Goal (maintainer): less custom DI code, more reliance on Symfony and Drupal, while keeping service declarations next to the code. Details of the current state are in the dependency-injection recap below.

Finding: the most complex custom DI code exists for one feature, **implicit registration of every class as a tentative private service** (`ClassInspector_ClassAsPrivateService`, `ServiceDefinitionUtil::TENTATIVE_TAG`), with unused ones removed later. In Drupal this needs several workarounds in service_discovery:
- `ModuleServiceProviderBase` registers into a separate Symfony `ContainerBuilder`, setting its private `compiler` and `parameterBag` through reflection, because Drupal makes services and aliases public;
- `ResilientServiceAlias` ignores Drupal's `setPublic(true)` via `debug_backtrace()`;
- `NeutrallyDecoratingPass` hides `RemoveUnusedDefinitionsPass` from the installer's filter (`NormalInstallerServiceProvider`).

In the ock and renderkit container recordings only about 8 services come from implicit registration (inspectors, the `*Namespace` objects, `ParamToEgg_ParamTypeAsServiceId`). Not yet checked: whether the snapshotter hides inlined private services.

History: `TODO.md` lists Symfony-style scanning as discarded, with the note "make the symfony discovery work in Drupal by not making all services public". This proposal reverses that on purpose.

Proposal:
1. **Explicit registration only.** A class or static method becomes a service only with `#[AsService]`. Add it to the implicitly registered services, then delete the tentative-service machinery, the ContainerBuilder swap, `ResilientServiceAlias` and `NeutrallyDecoratingPass`.
2. **Public by default, private on request.** `#[AsService]` registers public services, matching Drupal (`ContainerBuilder::register()` sets public) and OCK's generated code, which fetches dependencies through PSR-11 `get()`. `#[AsService(public: false)]` replaces `#[PrivateService]`. Drupal's `ContainerBuilder` does not override `setDefinition()`, so private definitions stay private. In the installer, unused private services are not removed. This is harmless for declared services. Aliases are always public in Drupal (`setAlias()` override). That is no longer a problem.
3. **Configuration by Symfony.** Discovered definitions are autowired and autoconfigured. Tags come from `registerForAutoconfiguration(SomeInterface::class)->addTag()` where a tag matches an interface, otherwise from `registerAttributeForAutoconfiguration()`. Both are handled by Symfony's default passes (`ResolveInstanceofConditionalsPass`, `AttributeAutoconfigurationPass`), which Drupal's container runs. This replaces `ServiceModifierAttributePass` and `ServiceModifierInterface`. Aliases come from Symfony's `#[AsAlias]`.
4. **One small registrar instead of the fact pipeline.** It uses class-files-iterator plus plain reflection to read `#[AsService]` and `#[AsAlias]`, and replaces the inspectors and decorators in dependency-injection. dependency-injection then no longer depends on class-discovery. Package providers (`OckPackage`, `AdaptismPackage`, `EggPackage`) and module providers all use it. The single `#[RequireModule]` (`Formula_LayoutId`) becomes a small check in the registrar instead of two decorator classes.
5. Separate tracks, unchanged: DID removal (`#[GetService]` → `#[Autowire]`) and parametric services.

Naming: **`#[AsService]`** rather than `#[Service]`:
- it follows Symfony's `As*` convention ("this acts as a …"): `#[AsAlias]`, `#[AsDecorator]`, `#[AsEventListener]` (also on methods), `#[AsCommand]`;
- neither Symfony (7.4.20) nor Drupal core (11.4.9) has an `#[AsService]`; some third-party packages do, e.g. frzb/dependency-injection;
- the maintainer recalls an earlier `#[Service]` attribute on **parameters**, with a purpose similar to `#[Autowire]`. The `As` prefix avoids confusion between "register this as a service" and "inject a service here";
- it is public surface for module authors, so rename before the first alpha (about 36 uses of `#[Service]` plus 6 of `#[PrivateService]`).

Effect on `symfony/config`: the explicit approach does not need it. `registerClasses()` registers every class in a directory, which would bring back the "all public, broken services stay" problem in Drupal. Leaning: do not depend on `symfony/config`; the spike in the dependency-injection recap becomes unnecessary.

Open:
- Keep the aliases for interfaces with a single implementation in a package (`PackageInspector_SinglyImplementedInterfaceAliasDecorator`), or require `#[AsAlias]`? Symfony only offers this through `registerClasses()`.
- After the reduction, keep dependency-injection as a tiny package, or fold the registrar into service_discovery? Packages need it outside Drupal too (package tests use a plain Symfony container).

### Leaning: remove egg

Egg, DID's `ValueDefinition` and Symfony's `Definition`/`Reference` are three versions of the same idea: "a recipe for a value, resolved later by a container". Egg is used by adaptism (adapter definitions), OCK (`ParamToFormulaEgg`, `Formula_ContainerProxy_Egg`) and the ock module's service provider. It does not appear in plugin code.

Plan: make adapters/handlers and plugin declarations tagged container services, so the container does the "hatching" (see the TODO.md question "Can everything be a service?"). Egg is internal, so this can happen after the first alpha.

The maintainer is happy to see egg disappear. A distinction to keep in mind:

| | Lives when | Hatched by | Serializable | Service visibility |
|---|---|---|---|---|
| Symfony `Definition` / `Reference` | container build | the container compiler | via the dumped container | private works |
| Symfony service closure / locator | runtime, wired at build time | only the container that built it | no | private works |
| Egg | runtime, passed around freely | anyone with a PSR-11 container | yes | public only |

Consequences:
- The runtime replacement for eggs is **generated PHP code** calling `$container->get(…)`: portable and serializable, and OCK already produces it. Closures are portable but not serializable.
- **The public-services limitation remains** with generated code. It is less painful in Drupal (services are public by default) than in plain Symfony.
- Possible fix: discovery runs at container build time, so OCK knows every service that factory parameters need. It could register one internal service holding a service closure per needed service (Drupal's dumper supports service closures), and generated code would fetch dependencies from it. Private services would then work too.
- To keep the OCK core framework-independent, generated code should only depend on PSR-11. Build-time wiring with Symfony definitions belongs in the Drupal or Symfony integration layer.

### Leaning: specialize adaptism and move it into OCK

Background: in D7 cfr (`7.x-2.x`), one `Configurator` object had `confGetForm()`, `confGetSummary()`, `confGetValue()` and `confGetPhp()`.

| cfr (D7) | OCK |
|---|---|
| `ConfiguratorInterface` (all four capabilities) | **formula** (pure description) + separate capabilities |
| `confGetForm()` | `FormatorD8Interface::confGetD8Form()` (in `modules/ock`) |
| `confGetSummary()` | `SummarizerInterface::confGetSummary()` |
| `confGetPhp()` | `GeneratorInterface::confGetPhp()` |
| `confGetValue()` | `EvaluatorInterface::confGetValue()` (built on the generator) |
| `PossiblyOptionless`, `ConfEmptyness` | `Optionlessness` |
| `ValueToValue` | `V2V` / `Formula_ValueToValue` |
| `Configurator/Group`, `Optional`, `Sequence`, `Id` | `Formula/Group`, `Optional`, `Sequence`, `Id` / `Drilldown` |
| cfrreflection: `ParamToConfigurator`, `CallbackToConfigurator`, `InterfaceToConfigurator` | `Discovery` (`ParamToFormulaEgg`), `Formula_Iface` |
| cfrfamily / cfrrealm: `IdToConfigurator`, `TypeToConfigurator` | `IdToFormula`, `Plugin/Registry` |
| cfrpreset | `ock_preset` |
| entdisp, listformat, cfrblock | to be ported into renderkit |

OCK splits the configurator into:
- **formula**: a pure description;
- separate capabilities: **formator** (Drupal form, in `modules/ock`), **summarizer**, **generator**, and **evaluator** (built on the generator).

Benefits of the split: no Drupal in the core; new capabilities can be added without touching every formula; a single source of truth for evaluation. The cost: something must find the capability for a given formula. That is what adaptism does: the formula is the "seed" that grows capabilities. 45 of about 60 `#[Adapter]` declarations are generators, formators or summarizers.

In practice, all adaptism usage involves formulas. Chaining (`SpecificAdapter_Bridge`, created automatically in `AdapterMap_FixedSerializable`) is used for **formula → formula replacements** (`FreeParameters`, `InstanceFactory`, `Iface` → plugin map, Select ↔ DrupalSelect), followed by formula → capability.

Proposed redesign:
- **Capability handlers**: formula → capability, one hop.
- **Formula replacements**: formula → simpler formula, applied in a loop of any length with cycle detection. Error messages list the path that was tried.
- Move this into OCK, with a typed API, e.g. `$capabilities->get($formula, SummarizerInterface::class)`. This replaces the general `UniversalAdapterInterface`, which is passed through about 133 places.
- Keep the chaining general internally. Extract it again only if a consumer outside OCK appears.
- The public attribute names must describe meaning, not mechanism, e.g. `#[FormulaHandler]` / `#[FormulaReplacement]`. Then a more general chaining later is not a BC break.
- Keep the formula-level concept apart from the plugin-level `#[OckAdaptee]` (e.g. `EntityBuildProcessor_FromBuildProcessor`). They are different concepts, and both are currently called "adapter".

Known issues in the current implementation:
- `SpecificAdapter_Bridge` uses a `static $recursion` counter shared by all bridges. At depth > 5 it silently returns `null`, so a deep chain and a cycle both look like "no adapter".
- Select ↔ DrupalSelect convert in both directions, which forms a cycle. The DrupalSelect direction is probably better as a formator handler than as a replacement.

### Decided: keep the Text layer in the OCK core

`Text`, `TextLookup`, `Translator` and `Markup` exist for good reasons. Drupal's `TranslatableMarkup` does not offer these:
- **No hidden service dependencies.** `TranslatableMarkup::getStringTranslation()` falls back to `\Drupal::service('string_translation')`, and the target language is implicitly the current one. `TextInterface::convert(TranslatorInterface)` makes both explicit.
- **Structural composition.** Nested texts and lists (`Text::ul()`, `ol()`, `concat()`, `concatDistinct()`) stay structured until `convert()`. This is needed for summaries of nested configurations: the summary tree mirrors the formula tree.
- **Language-independent caching.** A `TranslatableMarkup` serializes without its translator, but it does not survive a string concat, e.g. building a comma list. From then on it is translated in the current language. OCK text trees stay untranslated until the single `convert()` at the end, so whole summaries can be cached once and translated later.

Follow-ups:
- On the Drupal side, accept `TranslatableMarkup` input and convert it into `Text_Translatable` (same source string and arguments) instead of rendering it early.
- Trim the public Text API (around 16 classes; `t`, `s`, `i`, `tIf`, `tOrNull`, builders, …) when settling the public surface.
- Translation string extraction: see the pre-release roadmap.

### Deferred: caching of compiled configuration

`Evaluator_GeneratorEval::confGetValue()` currently generates PHP and `eval()`s it on every call. Writing the generated code to disk, keyed by a hash of the configuration, is easy to add later and does not affect the API.

## Package recap

A per-package review, bottom-up, to keep the history and reasons behind the current split.

The plan is not to move code back and forth now. Separate packages cost little while they are internal and released in lockstep, and merging later is cheap (Composer `replace`). The exception is adaptism → OCK, which enables a design simplification and is worth doing early.

### History of the split

- **#126 "Split class-discovery package"** (open): *"The class-discovery package has some parts that feel too much of a buy-in, for somebody who wants to use just that package."* The split targeted **standalone users**. Planned parts: `ock/reflection` (done, #132), `ock/class-files-iterator` (done, #139), and `ock/facts-discovery` for the fact-based discovery (not done as such).
- **#155 "Prepare releases for mature packages"** published `1.0.0-alpha1` (2025-05-30) for **helpers**, **reflector-aware-attributes** and **class-files-iterator**. That was the earlier maturity judgement.
- **#180 / PR #181 "Extract 'ock/text' package"** (open, 2026-10): the Text layer was about to become its own package. Revisit this when reviewing OCK.
- **#148 "Extract new package 'ock/reflector-aware-attributes'"** (open): "still some steps left".
- **#144 "Create tests in 'ock/reflection'"** (open).
- **PR #159 "Add PerformanceTest and compare with symfony finder"** (open, no results yet).

### Dependency tiers (actual `use` statements)

```
tier 1 (no ock deps):  helpers, reflection, reflector-aware-attributes, codegen-tools
tier 2:                class-files-iterator, egg (uses dependency-injection, undeclared)
tier 3:                class-discovery → dependency-injection → di-discovery, adaptism → object-construction-kit
```

### helpers (`1.0.0-alpha1`)

- **Purpose:** shared utilities for packages that should not depend on each other. Created in May 2024 to deduplicate `MessageUtil`.
- **Actual use outside the package:**
  - `MessageUtil::formatValue()` / `formatReflector()` (39 files; formats values in exception messages);
  - `array_filter_instanceof()` (5 files), `is_valid_qcn()` (1 file);
  - unused: `scandir_known()`, `scandir_expected()`, `project_root_path()`, `preg_grep_keys()`, `to_array()`, `is_valid_identifier()`, `IA_Array`, `IA_Concat`.
- **Standalone value:** low (a grab-bag).
- **Alternatives:** `get_debug_type()` / `var_export()` cover part of `MessageUtil`.
- **Distance from stable:** technically close (3 test files), but it contains dead code.
- **"Only the helpers" argument:** relevant only for a package that needs `MessageUtil` and nothing else from ock. Currently that is only class-files-iterator, which declares helpers but does not use it.
- **Suggestion:** keep it. Prune the unused functions before a stable release.

### reflection (not released)

- **Purpose:** split from class-discovery (#132, April 2025). It treats a class and a method uniformly as a **factory** (`FactoryReflectionInterface`: `getReturnClass()`, `getParameters()`, `isCallable()`, …). That lets "any class *or* static method" be a plugin, service or adapter with one code path.
- **Consumers:** class-discovery (12 files), dependency-injection (8), di-discovery (4), OCK (3), adaptism (2), testing (1).
- **Unique concept:** the class/method-as-factory unification. No known direct equivalent (roave/better-reflection etc. solve other problems).
- **Standalone value:** modest, niche.
- **Distance from stable:** the farthest of tier 1.
  - Only 1 test file (#144 open).
  - `MethodReflection.php` is excluded from PHPStan because it crashes PHPStan.
  - The API is large: `ClassReflection` has 26 public methods, `FactoryReflectionInterface` 21.
- **Suggestion:** keep it internal. It is the first candidate to merge with class-discovery if packages get collapsed.

### reflector-aware-attributes (`1.0.0-alpha1`)

- **Purpose:** split out in May 2025 (#148). It lets attribute instances know the symbol they are attached to:
  - `AttributeConstructor::getReflector()`, callable **inside the attribute constructor** (via a static slot set during instantiation);
  - `ReflectorAwareAttributeInterface`, with the reflector set after construction;
  - plus `get_attributes()`, `AttributeReader` and instantiator decorators.
- **Consumers:** 12 files in class-discovery, dependency-injection, di-discovery and OCK. Used to fill defaults from the parameter type and to validate placement.
- **Alternatives:** **crell/attributeutils** (Larry Garfield, about 665k downloads, PHP 8.1+). It has `FromReflectionClass`, `FromReflectionMethod`, `FromReflectionProperty`, `FromReflectionParameter` and others, with `fromReflection()` called **after** construction. That is equivalent to the interface mechanism here. It adds inheritance, sub-attributes, scopes and caching.
- **Unique part:** constructor-time access, so all readonly properties can be computed in the constructor. A real but narrow difference, and it relies on static state.
- **Standalone value:** moderate, but largely covered by crell/attributeutils.
- **Distance from stable:** close (small API, 8 test files, already alpha). #148 has remaining steps.
- **Open question:** position it against crell/attributeutils ("lightweight, constructor-time access"), or switch to crell/attributeutils (this would drop constructor-time access).

### codegen-tools (not released)

- **Purpose:** string-based PHP code generation. It predates the monorepo; the README still mentions `callback-reflection`. Contents:
  - `CodeGen`: expression builders such as `phpCall()`, `phpConstruct()`, `phpValue()`, `phpArray()`;
  - `ValueExporter` and `ExportableAttributeInterface`;
  - `CodeFormatter` and `Aliasifier`;
  - `PhpUtil`, which has OCK-specific helpers: `phpAdapt()`, `phpDecorate()`, placeholders.
- **Consumers:** OCK (16 files, mostly `Generator_*`), di-discovery (3), and **the modules (12 files)**, through custom formulas with their own generators. So `CodeGen` is part of the API for **custom formula authors**, and belongs in the public surface inventory.
- **Alternatives:** none is a drop-in replacement for cheap nested expression strings:
  - nikic/php-parser builders and pretty printer (robust, heavier);
  - symfony/var-exporter (exports values via a hydrator, not constructor calls);
  - nette/php-generator and laminas-code (classes and files, not expressions).
- **Standalone value:** low. The package mixes generic and OCK-specific parts.
- **Distance from stable:** medium (27 test files, stale README, mixed API).
- **Suggestion:** keep it for now. Move the OCK-specific `PhpUtil` parts into OCK when that area is next touched. Decide which `CodeGen` methods are public for custom formula authors.

### class-files-iterator (`1.0.0-alpha1`)

- **Purpose:** list the classes in a PSR-4 directory: the first step of all discovery. Split from class-discovery (#139). Contents:
  - `ClassFilesIA` (file → class) and `ClassNamesIA`, as immutable `IteratorAggregate`s;
  - `NamespaceDirectory`, a navigable PSR-4 directory value object (`fromClass()`, `parent()`, `subdir()`, `package()`, `vendor()`, relative paths).
- **Consumers:** class-discovery (7 files), testing (fixture/recording paths), modules/ock (1 file: `NamespaceDirectoriesIA_ModuleNamespaces`, which appears to be unused).
- **Unique concepts:** deterministic alphabetical order (stable discovery and snapshots); immutable, stateless iterables; navigation from a seed class ("scan the namespace of this class's package").
- **Alternatives:**
  - **composer/class-map-generator**, from the Composer team. It finds classes by **tokenizing, without loading them**, which avoids this package's known weakness ("missing traits still cause fatal error").
  - spatie/structure-discoverer;
  - symfony/finder plus class detection (see PR #159);
  - olvlvl/composer-attribute-collector (attributes collected at Composer dump time).
- **Distance from stable:** **the closest of all packages**. 100% coverage, enforced in CI. The API was renamed and settled in June 2025 and has not changed since.
- **Open questions:**
  - Keep "loads classes" and position it as lightweight and navigable, or adopt token-based detection?
  - Keep the "IA" suffix naming? It is cheaper to rename now (alpha) than after a stable release.
- **Suggestion:** a promotion candidate. Drop the unneeded `helpers` dependency.

### class-discovery (not released)

- **Purpose:** the generic discovery pipeline under all attribute discovery in the stack:
  ```
  ClassFilesIA → ReflectionClassesIA → Inspector (package / class / factory) → FactsIA
     (CFI)        (loads classes)        yields "facts" (key → value)           iterable result
  ```
  Also `Util/AttributesUtil`, `Util/ReflectionTypeUtil`, and exceptions.
- **Consumers, by symbol:**
  - `MalformedDeclarationException` (39 files; effectively the whole stack's "bad declaration" exception);
  - `ReflectionClassesIAInterface` (25), `FactoryInspectorInterface` (22), `AttributesUtil` (18), `ClassInspectorInterface` (16), `ReflectionTypeUtil` (15), `FactsIAInterface` (13), `PackageInspectorInterface` (11).

  These are spread across dependency-injection, di-discovery, adaptism, OCK, and the ock and renderkit modules. Each kind of discovery (services, adapters, plugins) is an inspector, with decorators for cross-cutting behaviour.
- **Relation to #126:** what remains is essentially the planned "facts-discovery" plus the `ReflectionClassesIA` bridge and utilities. The split is basically complete. Close #126, or reword it if the bridge should be separated from the inspectors.
- **Unique concept:** one discovery pass with pluggable inspectors at package, class and factory level, and composable lazy results. The most original part of the lower layer.
- **Alternatives,** partial and per use case:
  - Symfony DI `registerAttributeForAutoconfiguration()` (services);
  - Drupal core `AttributeClassDiscovery` (plugins, fixed subdirectories);
  - olvlvl/composer-attribute-collector (build time);
  - spatie/structure-discoverer.
- **Distance from stable:** far.
  - Only 2 test files, for a package everything depends on.
  - Utility grab-bags make up much of the API.
  - Every package depends on class-discovery just to throw `MalformedDeclarationException`.
- **Robustness issue (Drupal):** a module that opts in scans its whole namespace and loads every class. A class using a trait from an optional or uninstalled dependency causes an uncatchable fatal error during the container rebuild. Drupal core avoids this by scanning only specific subdirectories. Options: document it, restrict scanning to configured subdirectories, or pre-filter files by tokens.
- **Suggestion:** keep it internal. If packages are collapsed, `reflection` + `class-discovery` is the natural pair. Consider moving the common exceptions somewhere more basic.

### egg (not released)

See "Leaning: remove egg" under architecture decisions. Summary:
- **History:** created in May 2024 by renaming DID's "ContainerToValue" (CTV) concept.
- **Its two real jobs:**
  1. lazy, serializable adapter definitions (`AdapterMap_FixedSerializable`);
  2. resolving factory parameters to container values (`ParamToEgg*`, `SpecificAdapter_FreeParameters`, `Formula_ContainerProxy_Egg`).
- **Replacements:**
  - adapters as container services, with one service closure per adapter (Drupal's dumper supports service closures, but not service locators);
  - autowiring for services;
  - generated code for runtime plugin construction.
- **Suggestion:** remove it together with moving adaptism into OCK; both need the "handlers and plugins as services" rework.

### dependency-injection (not released)

- **History:** started in July 2024 as the successor of DID. Instead of DID's own container model, it extends Symfony DI. It added `#[Autoconfigure]` support (July 2024), service conditions (August 2024), and removed `symfony/config` (#98, November 2024).
- **Key constraint:** Drupal core requires `symfony/dependency-injection` but **not `symfony/config`**, and many Symfony DI features need `symfony/config`. Checked against Symfony 7.4.20 / Drupal 11.4.9:

  | Symfony feature | Needs `symfony/config`? | Works in Drupal today? |
  |---|---|---|
  | `#[Autowire]`, `#[AutowireIterator]`, `#[AutowireCallable]`, `#[AutowireServiceClosure]`, `#[Target]`, `#[AsDecorator]` | no | yes, for autowired services |
  | `registerAttributeForAutoconfiguration()` (in code) | no | yes |
  | `#[Autoconfigure]`, `#[AutoconfigureTag]` (`RegisterAutoconfigureAttributesPass` uses `YamlFileLoader`) | **yes** | no: fatal error once a class uses them |
  | `#[AutowireInline]` (also uses `YamlFileLoader`) | **yes** | no |
  | Class scanning (`registerClasses()` / `resource:`), `#[AsAlias]`, `#[Exclude]`, `#[When]`, aliases for singly-implemented interfaces (all in `Loader\FileLoader`) | **yes** | no |

  Drupal's own `YamlFileLoader` has no `resource:` support. The closest thing in core is `HookCollectorPass`, which registers `#[Hook]` classes as autowired services, but only for hooks.

- **Features and their Symfony equivalents:**

  | ock feature | Uses | Symfony equivalent |
  |---|---|---|
  | Package/module scanning (`PackageServiceProviderBase`, `ServiceProvider_FactsIA`) | every module that opts in | `registerClasses()` (needs config) |
  | `#[Service]` / `#[PrivateService]` on a class | 27 | scanning + `#[Autoconfigure(public: true)]` (needs config) |
  | `#[Service]` on a static method (return type = service) | 11 | none; `#[Autoconfigure(constructor: …)]` only covers self-construction; otherwise `factory:` in YAML |
  | `#[ServiceTag]` | 19 | `#[AutoconfigureTag]` (needs config) or `registerForAutoconfiguration()` in code |
  | Respects `#[AsAlias]`; aliases for singly-implemented interfaces | — | built into `registerClasses()` (needs config) |
  | Conditions (`#[RequireModule]` in service_discovery) | 1 (`Formula_LayoutId`) | `#[When]` is environment-only; nothing module-based |
  | Parametric services (`#[ParametricService]`, `#[GetParametricService]`, `#[GetParametricArgument]`) | 6 renderkit files | `#[AutowireInline]` partly (needs config); see below |

- **Why #98 removed `symfony/config`** (maintainer's memory): it did not seem compatible with Drupal core at the time, and caused conflicts, maybe with Drupal's custom container/DI logic. Unknown whether this still applies.
  - Current code shows no obvious blocker: `drupal/core` has no Composer `conflict` with it and requires Symfony `^7.4`. Drupal's `ContainerBuilder` only overrides `register()` (public by default), `setAlias()` (public), `setParameter()` (lowercase names) and `set()`, and disables resource tracking.
  - Not proof. **Spike to decide:** in ddev, `composer require symfony/config:^7.4`, let one module's service provider call `registerClasses()` on its own namespace (with `exclude` for risky directories), rebuild the container, and run the kernel tests. The drupal-testing snapshotters (`Snapshotter_ContainerDefinitions`, `_ContainerAliases`) show exactly which definitions change.
  - If the spike succeeds (option A), most of dependency-injection can be replaced by Symfony features. What remains ock-specific (static-method factories, module conditions, parametric services) is small enough for the service_discovery module. If it fails (option B), keep the own scanner and attributes, slimmed down to what is used.
  - Superseded (leaning): see "Leaning: explicit service registration, configured by Symfony". `registerClasses()` would register every class, so explicit `#[AsService]` with Symfony autoconfiguration is preferred, and the spike is no longer needed.
- **Rationale for local declarations** (maintainer): updating `*.services.yml` all the time while working on ock and renderkit was painful. A central YAML file easily gets out of sync, even more so with `git cherry-pick` or `rebase`, while classes are being moved and renamed to find the final shape of the product. Services for classes in composer packages should also be easy to declare. Everything *could* be declared in YAML, but declarations next to the code are much easier to maintain. `#[Service]` on static methods is one convenience within this.
  - Drupal moves in this direction for plugins (attributes) and hooks (`#[Hook]`), but services are still central YAML in core.
  - service_discovery and controller_attributes both solve "declare it next to the code" for Drupal (services and routes). That could be a pitch of its own, independent of OCK, or something to raise with Drupal core. Check whether a core issue for attribute-based or `resource:`-based service registration exists.
- **Rationale for parametric services** (maintainer): a constructor or other method gets the correct variant of a service passed in directly, so the parameter type describes the actual object, instead of `callable` or `SomethingFactory`.
  - Symfony's closest feature is `#[AutowireInline]`: an inline, autowired definition at the injection point, with class/factory and arguments, keeping the real parameter type.
  - What it lacks is **propagation**. In `Formula_FieldName`, the entity type ID arrives as `#[GetParametricArgument(0)]` and is passed down to the nested `#[GetParametricService]` dependency (`TextLookup_FieldName`). With `#[AutowireInline]`, nested dependencies are autowired generically.
  - **Planned:** an example changeset comparing three versions of one chain (`Formula_FieldName` → `TextLookup_FieldName`, plus a consumer): (1) as now, (2) with `#[AutowireInline]` (fits the `symfony/config` spike), (3) with an explicit factory service.

### Still to review

di-discovery (removal already decided), adaptism, object-construction-kit (including the Text layer and #180), testing, drupal-testing; and the modules controller_attributes, service_discovery and themekit.

## Roadmap

### Before the first alpha (public surface)

1. **Public surface inventory.** List every attribute, interface and base class used by plugin and module authors. Mark each as keep, rename, move into `Ock\Ock`, or hide.
   Today, plugin code in renderkit also uses `Ock\DependencyInjection\Attribute` (36), `Ock\DID\Attribute` (24 files), `Ock\CodegenTools\Util` (10) and `Ock\Adaptism\…`. The public surface should live in `Ock\Ock` (plus Symfony's `#[Autowire]`).
2. **Remove DID from user code**: `#[Autowire]` support, replace `#[GetService]` and friends, delete the package.
   Also decide the service declaration story, since its attributes are public surface for module authors: explicit `#[AsService]` registration (see "Leaning: explicit service registration, configured by Symfony"), and the parametric-services comparison (see the dependency-injection recap).
3. **Settle the renderkit design** (see the open questions). Prototype one vertical slice first: the build-processor family (`BuildProcessor`, `EntityBuildProcessor`, `LabeledFormat`, `LabeledEntityBuildProcessor`, `FieldDisplayProcessor`).
4. **Settle the public names** for formula handlers/replacements (adaptism) and the Text API.
5. **Test translation string extraction.** Check whether localize.drupal.org's extractor (potx) picks up labels in `#[OckPluginInstance('id', 'Label')]` and strings in `Text::t(...)`. If not, labels and summaries never reach translators. Possible fixes: generate a `.pot` file with a drush command, or provide our own extraction.
6. **Drupal integration in renderkit**, porting entdisp / listformat / cfrblock:
   - a field formatter for entity reference fields (`EntityDisplayInterface`, `EntitiesListFormatInterface`);
   - a Views row plugin and field handler (`EntityDisplayInterface`), and a Views style (`ListFormatInterface`);
   - a Block plugin with a deriver over presets;
   - config-time context in the form element (e.g. entity type, as in D7 `#cfrplugin_context`);
   - things D7 did not need: config schema, cacheability metadata, config dependencies.
   Keep batching (`buildEntities()`) for Views rows and multi-value fields.
7. **Demo**: make the ock_demo / Umami site show the integration. Consider a Drupal recipe (`recipes/`) as a fast "try it" path.
8. ~~**Resolve licensing**~~: done (all packages MIT, see "Licensing").
9. **Release ock + renderkit as alpha**: lockstep versions, internals `@internal`, fixed composer metadata (descriptions, types, declared dependencies).

### After the first alpha (internal, no BC concern)

10. Make adapters/handlers and plugins container services; remove egg.
11. Move the specialized adaptism into OCK.
12. Merge or shrink the remaining lower-level packages, **before 1.0**.
13. Cache compiled configuration code on disk.
14. Decide which lower-level packages, if any, deserve a standalone release.

### Housekeeping

- Fix undeclared dependencies:
  - `object-construction-kit` and `egg` use `Ock\DependencyInjection` without requiring it;
  - `object-construction-kit` requires `di-discovery` but does not seem to use it;
  - `service_discovery` uses `Ock\DID` and `Ock\Ock` without declaring them.
- Add missing `description` / `type` in several `composer.json` files.
- Remove outdated Travis/Coveralls badges and stale README references (codegen-tools still mentions `callback-reflection`; renderkit still points to the D7 entdisp / listformat / cfrpreset).
- Fix the adaptism README example (`$rgb` vs `$rgbColor`).
- `class-files-iterator` declares `ock/helpers` but does not use it: remove the dependency.
- The modules require their ock dependencies with `"*"` (e.g. renderkit), while packages use `"self.version"`. With `"*"`, lockstep is not guaranteed. Use `self.version` (or exact versions) everywhere.
- `NamespaceDirectoriesIA_ModuleNamespaces` in modules/ock appears to be unused: check, then remove.
- Prune unused functions in `helpers` before a stable release.
- Add tests for `egg` (if it stays) and `drupal-testing`.

## Open questions

### Renderkit design

Problem: about half of renderkit's 30 interfaces are the same operation with different combinations of context arguments, e.g. build → build with + entity, + label, + entity + label, and list variants. Lifting plugins like `EntityBuildProcessor_FromBuildProcessor` work around this by hand.

Idea under discussion: separate the **output axis** from the **input axis**.
- Output: one interface per output kind (e.g. "returns a render array").
- Input: the inputs a plugin needs.
- Trap: inputs are contravariant. A plugin fits a slot if *its required inputs ⊆ the inputs the slot provides*. PHP's subtyping gets this backwards ("implements more interfaces" ≠ "needs fewer inputs"), so OCK must do the matching, not PHP.

Candidate shapes:
- **`__invoke()` with reflected inputs** (favoured so far). The output interface has no method; implementations have `__invoke(...)` returning the output type, enforced by a custom PHPStan rule, because PHP has no bottom type for parameters. OCK reads the `__invoke()` parameters as required inputs, matches by the subset rule, and generated code binds the plugin to the slot's shape. Decorators receive inner plugins as `\Closure`s with a fixed phpdoc signature. Constructor/factory parameters = configuration; `__invoke()` parameters = runtime inputs.
- **`withX()` withers**: native PHP types, natural partial application, but a clone per call, temporal coupling, awkward batching. OCK still has to do the matching.
- **Context object** (`process(array $build, RenderContext $ctx)`) plus input markers/attributes.

Open sub-questions:
- Match runtime inputs by parameter **name**, **type**, or both? Names would become public API.
- How to keep batching (`buildEntities()`)?
- How to express a plugin producing more than one output, e.g. a render array plus cache metadata?
- How is **config-time context** (e.g. entity type, which narrows the offered plugins and options) modelled next to runtime inputs? Reuse the existing `Formula/Contextual` / `ContextProviding`, or redesign along with the slice?
- Same-shape but semantically different types (`ImageProcessor` vs `BuildProcessor`): another input marker, or a separate output interface?

### Packaging

- Should the OCK core stay a separate Drupal-independent package? Middle path under consideration: keep the code free of Drupal (enforced by PHPStan/deptrac), but ship it inside the `drupal/ock` project (e.g. `lib/` autoloaded as `Ock\Ock\`), and extract it again if a non-Drupal consumer appears.
  - Check: does drupal.org's Composer metadata honour a module's own `autoload` section?
  - Check: does Drupal security team coverage extend to external Packagist dependencies? (Probably not, which would favour shipping inside the drupal.org project.)
  - Corporate allowlists also favour this: `drupal/ock` + `drupal/renderkit` would be 2 approvals instead of about 15 (see "Corporate dependency allowlists").
- Are `controller_attributes` and `service_discovery` still needed, given what Drupal 11 core now provides for routes, services and attributes? Verify against current core before investing more.

### Adaptism

- What was the original chained adapter case? It is not remembered right now. Check it against the proposed "replacement loop + one-hop handler" design when it turns up.
- Final names for formula handlers/replacements, and for the plugin-level `#[OckAdaptee]` concept.

### Misc

- PHP: check the status of `never` as a parameter type (a bottom type), which would allow an actual `__invoke()` interface for the output axis.
- Localized list formatting ("A, B and C" vs CJK separators) for `Text_List`, possibly via ICU. Check what PHP's intl extension offers today.
