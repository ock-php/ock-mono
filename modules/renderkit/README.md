# Renderkit

Renderkit is a compositional framework for the generation and processing of Drupal render arrays.

An "entity display handler" is an object that builds render arrays from entities. This may be for a specific field, an entity title link, or an entire entity displayed in a view mode with entity_view().

Some entity display handler classes take one or more other handlers as constructor arguments, e.g. to concatenate the output, or wrap it in a container element.

Besides entity display handlers, there are also classes and interfaces for lists, image derivatives, and more. All of them deal with render arrays somehow.

## Plugin layer (EntDisP and friends)

Many of the classes and interfaces in Renderkit will be registered as plugins and plugin types with [CfrPlugin](https://drupal.org/project/cfr).

Additional modules can be installed to integrate some of these plugin types into existing Drupal subsystems:

- [EntDisP (Entity Display Plugin)](https://drupal.org/project/entdisp) integrates `EntityDisplayInterface` plugins as Views row plugin, Views field handler, and as a field formatter for fields that reference other entities.

- [ListFormat](https://drupal.org/project/listformat) integrates `ListFormatInterface` plugins as Views style plugin.

- [CfrPreset](https://drupal.org/project/cfrpreset) allows to save and reuse plugin configurations, and export them with features.
