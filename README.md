# Renderkit

Renderkit is a compositional framework for the generation and processing of Drupal render arrays.

An "entity display handler" is an object that builds render arrays from entities. This may be for a specific field, an entity title link, or an entire entity displayed in a view mode with entity_view().

Some entity display handler classes take one or more other handlers as constructor arguments, e.g. to concatenate the output, or wrap it in a container element.

Besides entity display handlers, there are also classes and interfaces for lists, image derivatives, and more. All of them deal with render arrays somehow.

## Plugin layer (EntDisP and friends)

There are (or will be) other modules that expose renderkit objects as plugins. More information in the README.md of the respective modules.
 
Most notably this is (or will be) [EntDisP (Entity Display Plugin)](https://drupal.org/project/entdisp), which esposes entity display handlers as plugins. The EntDisP README.md contains a lot of information about the plugin architecture.
