# Class discovery

This package provides components and abstractions to help with all kinds of discovery operations across php class files.

Main concepts:
- [`ReflectionClassesIA*`](src/ReflectionClassesIA/ReflectionClassesIAInterface.php):\
  IteratorAggregate that lists a special type of `\ReflectionClass` class objects.\
  Typically this is based on a `ClassFilesIA*` object.
- [`FactoryReflection*`](src/Reflection/FactoryReflectionInterface.php):\
  Interface for custom `ClassReflection` and `MethodReflection`, with methods that treat both of them as "factories".
- [`FactoryInspector*`](src/Inspector/FactoryInspectorInterface.php):\
  Inspects a classes or methods, to find whatever you might be looking for.
- [`Discovery*`](src/Discovery/DiscoveryInterface.php):\
  IteratorAggregate to discover objects found by an inspector in reflection methods.
