# Class files iterator

This package provides IteratorAggregate implementations to iterate through class files.

Main concepts:
- [`ClassFilesIA*`](src/ClassFilesIA/ClassFilesIAInterface.php):\
  IteratorAggregate that lists class names keyed by their file names.
  - [`NamespaceDirectory`](src/NamespaceDirectory.php):\
    Main implementation representing a PSR-4 class files directory.\
    It provides additional methods to navigate to parent or child directories.
- [`ClassNamesIA*`](src/ClassNamesIA/ClassNamesIAInterface.php)\
  IteratorAggregate that lists class names by numeric keys.\
  This can be used if the file path is not relevant.

## Benefits

There are other packages which attempt to solve the same problem in different ways.

The benefits of this package are:
- Predictable (alphabetic) order of class files.\
  This is thanks to internal usage of `scandir()` with sort flag, instead of directory iterators.
- The "iterators" are stateless/immutable, and can be passed around as value objects.\
  This is thanks to them extending `IteratorAggregate` instead of `Iterator`.
- Code that needs a list of classes or class files can simply depend on the interfaces, and does not need to deal with directories.

## Limitations

Iterators for reflection classes are not part of this package.

## Usage

```php
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIAInterface;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;

// Basic creation for a PSR-4 directory.
$basic_namespace_dir = NamespaceDirectory::create($basedir . '/src', 'Acme\\Foo');

// Convenient creation with a "seed" class.
// The directory is determined automatically with ReflectionClass::getFileName().
$namespace_dir = NamespaceDirectory::fromKnownClass(VenusFlyTrap::class);

// Iterate class names by integer key.
function foo(ClassNamesIAInterface $classNamesIA): void {
  foreach ($classNamesIA as $class) {
    assert(class_exists($class));
  }
}

// Iterate class names with file path as key.
function foo(ClassFilesIAInterface $classNamesIA): void {
  foreach ($classNamesIA as $file => $class) {
    assert(file_exists($file));
    assert(class_exists($class));
  }
}

// Get reflection classes.
function foo(ClassFilesIAInterface $classFilesIA): void {
  foreach ($classFilesIA as $file => $class) {
    try {
      $rc = new \ReflectionClass($class);
    }
    catch (\ReflectionException|\Error) {
      // Skip non-existing classes / interfaces / traits.
      // Skip if a base class or interface is missing.
      // Unfortunately, missing traits still cause fatal error.
      continue;
    }
    // Ignore if the class is defined elsewhere.
    if ($rc->getFileName() !== $file && realpath($rc->getFileName()) !== realpath($file)) {
      continue;
    }
    do_something($rc);
  }
}
```
