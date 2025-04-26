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
