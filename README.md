# OCK Monorepo

This is a monorepo for the OCK project. It contains stand-alone composer packages, and Drupal modules.

It also contains a Drupal installation powered by ddev.


## About OCK

OCK stands for "Object Construction Kit". It provides configuration-driven object composition.

A main current target platform is Drupal. However, a lot of the code is published as stand-alone composer packages, which could be used outside of Drupal.


## Licensing

Different parts of this repository use different licenses:

| Path | License |
|---|---|
| Everything not listed below, including the composer packages in `packages/` and the monorepo tooling | MIT, see [LICENSE](LICENSE) |
| `modules/` (Drupal modules) | GPL-2.0-or-later, as required on drupal.org |
| `config/`, `web/sites/` (derived from Drupal) | GPL-2.0-or-later |
| `.ddev/` (local ddev environment, including files from ddev add-ons) | Apache-2.0, like ddev itself |

Each package and module also declares its license in its own `composer.json`.

The machine-readable version is in [REUSE.toml](REUSE.toml) (see [reuse.software](https://reuse.software/)), and the license texts are in [LICENSES/](LICENSES/).

## Development: Local installation

```sh
# Start ddev, if not already done.
ddev start

# Download dependencies.
ddev composer install

# Install Drupal from existing configuration.
ddev drush si --existing-config

# Get the Umami default content.
ddev drush pmu demo_umami_content
ddev drush en demo_umami_content

# (optional) Generate a login link.
ddev drush uli --no-browser
```
