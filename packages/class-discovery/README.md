[![Build Status](https://secure.travis-ci.org/ock/class-discovery.png)](https://travis-ci.org/ock/class-discovery)

# Class discovery

This package provides components and abstractions to help with all kinds of discovery operations across php class files.

Main concepts:
- [`ClassFilesIA*`](src/ClassFilesIA/ClassFilesIAInterface.php):  
  IteratorAggregate that lists class names keyed by their file names.
  - [`NamespaceDirectory`](src/NamespaceDirectory.php):  
    Main implementation representing a PSR-4 class files directory.  
    It provides additional methods to navigate to parent or child directories.
- [`ReflectionClassesIA*`](src/ReflectionClassesIA/ReflectionClassesIAInterface.php):  
  IteratorAggregate that lists a special type of `\ReflectionClass` class objects.  
  Typically this is based on a `ClassFilesIA*` object.
- [`FactoryReflection*`](src/Reflection/FactoryReflectionInterface.php):  
  Interface for custom `ClassReflection` and `MethodReflection`, with methods that treat both of them as "factories".
- [`FactoryInspector*`](src/Inspector/FactoryInspectorInterface.php):  
  Inspects a classes or methods, to find whatever you might be looking for.
- [`Discovery*`](src/Discovery/DiscoveryInterface.php):  
  IteratorAggregate to discover objects found by an inspector in reflection methods.
- 
