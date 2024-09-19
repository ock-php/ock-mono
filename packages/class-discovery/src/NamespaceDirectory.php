<?php

namespace Ock\ClassDiscovery;

use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Value object representing a single namespace directory.
 */
final class NamespaceDirectory implements ClassFilesIAInterface {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  const CLASS_NAME_REGEX = /** @lang RegExp */ '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

  const CANDIDATE_REGEX = /** @lang RegExp */ '/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\.php|)$/';

  /**
   * Creates a new instance.
   *
   * @param string $directory
   *   Directory without trailing slash.
   * @param string $namespace
   *   Namespace without trailing separator, or '' for the root namespace.
   *
   * @return self
   */
  public static function create(string $directory, string $namespace): self {
    assert(!str_ends_with($directory, '/'), "Directory '$directory' must not end with slash.");
    $namespace = NsDirUtil::terminateNamespace($namespace);
    return new self($directory, $namespace);
  }

  /**
   * Creates a namespace directory based on a known class name.
   *
   * @param class-string $class
   *
   * @return self
   */
  public static function fromKnownClass(string $class): self {
    try {
      return self::createFromClass($class);
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }
  }

  /**
   * Creates a namespace directory based on a class name.
   *
   * @param class-string|string $class
   *   A class in the namespace directory.
   *
   * @return self
   *   Namespace directory of this very class file.
   *
   * @throws \ReflectionException
   *   Class does not exist.
   */
  public static function createFromClass(string $class): self {
    // Accept that `new \ReflectionClass()` will throw an exception if the class
    // does not exist.
    // @phpstan-ignore argument.type
    $reflClass = new \ReflectionClass($class);
    return self::create(
      dirname($reflClass->getFileName()),
      $reflClass->getNamespaceName(),
    );
  }

  /**
   * Constructor.
   *
   * @param string $directory
   *   Directory without trailing slash.
   * @param string $terminatedNamespace
   *   Namespace ending with separator, or '' for the root namespace.
   */
  private function __construct(
    private string $directory,
    private readonly string $terminatedNamespace,
  ) {}

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * @return static
   */
  public function withRealpathRoot(): static {
    $clone = clone $this;
    $clone->directory = realpath($this->directory);
    return $clone;
  }

  /**
   * @param string $namespace
   *
   * @return self|null
   */
  public function findNamespace(string $namespace): ?self {
    $namespace = NsDirUtil::terminateNamespace($namespace);
    if (!str_starts_with($namespace, $this->terminatedNamespace)) {
      return NULL;
    }
    if ($namespace === $this->terminatedNamespace) {
      return $this;
    }
    $l = strlen($this->terminatedNamespace);
    $directory = $this->directory
      . '/'
      . str_replace(
        '\\',
        '/',
        substr($namespace, $l, -1),
      );
    return new self($directory, $namespace);
  }

  /**
   * @return self
   */
  public function basedir(): self {
    $base = $this;
    while (null !== $parent = $base->parent()) {
      $base = $parent;
    }
    return $base;
  }

  /**
   * Gets the vendor namespace directory.
   *
   * @return self
   */
  public function vendor(): self {
    return $this->requireParentAt(1);
  }

  /**
   * Gets the package namespace directory.
   *
   * @param int $level
   *   Expected number of namespace fragments in the package namespace.
   *
   * @return static
   */
  public function package(int $level = 2): static {
    return $this->requireParentAt($level);
  }

  /**
   * @param int $level
   *
   * @return static
   */
  public function requireParentAt(int $level): static {
    $depth = substr_count($this->terminatedNamespace, '\\');
    return $this->requireParentN($depth - $level);
  }

  /**
   * Gets the nth parent directory, expecting it to exist.
   *
   * @param int $nLevelsUp
   *
   * @return static
   *
   * @throws \RuntimeException
   */
  public function requireParentN(int $nLevelsUp): static {
    if (null === $parent = $this->parentN($nLevelsUp)) {
      throw new \RuntimeException("No parent-!n namespace directory found for !dir / !nsp.");
    }
    return $parent;
  }

  /**
   * Gets the parent directory, expecting it to exist.
   *
   * @return self
   *
   * @throws \RuntimeException
   */
  public function requireParent(): self {
    if (null === $parent = $this->parent()) {
      throw new \RuntimeException(strtr(
        "No parent namespace directory found for !dir / !nsp.",
        [
          '!dir' => var_export($this->getDirectory(), TRUE),
          '!nsp' => var_export($this->getNamespace(), TRUE),
        ],
      ));
    }
    return $parent;
  }

  /**
   * Gets the nth parent directory, if it exists.
   *
   * @param int $nLevelsUp
   *
   * @return static|null
   */
  public function parentN(int $nLevelsUp): ?static {
    if ($nLevelsUp === 0) {
      return $this;
    }

    if ($nLevelsUp < 0) {
      $nLevelsUp += substr_count($this->terminatedNamespace, '\\');
      if ($nLevelsUp === 0) {
        return $this;
      }
      if ($nLevelsUp < 0) {
        return null;
      }
    }

    if (null === $parent = $this->parent()) {
      return null;
    }

    return $parent->parentN($nLevelsUp - 1);
  }

  /**
   * Gets the parent namespace directory, if it exists.
   *
   * @return self|null
   *   The parent, or NULL if it does not exist.
   */
  public function parent(): ?self {
    if ('' === $this->terminatedNamespace || '' === $this->directory) {
      return NULL;
    }

    if (FALSE === $pos = strrpos($this->directory, '/')) {
      $parentDir = '';
      $subdirName = $this->directory;
    }
    else {
      $parentDir = substr($this->directory, 0, $pos);
      $subdirName = substr($this->directory, $pos + 1);
    }

    if ($subdirName . '\\' === $this->terminatedNamespace) {
      return new self($parentDir, '');
    }

    $l = strlen($subdirName);
    if (!str_ends_with($this->terminatedNamespace, '\\' . $subdirName . '\\')) {
      return NULL;
    }

    if ('\\' . $subdirName . '\\' !== substr($this->terminatedNamespace, -$l - 2)) {
      return NULL;
    }

    return new self(
      $parentDir,
      substr($this->terminatedNamespace, 0, -($l + 1)),
    );
  }

  /**
   * @param string $fragment
   *
   * @return self
   */
  public function subdir(string $fragment): self {
    return new self(
      $this->directory . '/' . $fragment,
      $this->terminatedNamespace . $fragment . '\\',
    );
  }

  /**
   * @return string
   */
  public function getNamespace(): string {
    return rtrim($this->terminatedNamespace, '\\');
  }

  /**
   * Gets the last part of the namespace, without any separators.
   *
   * @return string|null
   *   Last namespace fragment, or NULL if this is the root namespace.
   */
  public function getShortname(): ?string {
    if ($this->terminatedNamespace === '') {
      // This is the root namespace.
      return null;
    }
    if (false === $pos = strrpos($this->terminatedNamespace, '\\', -2)) {
      // This is a one-level namespace.
      return \substr($this->terminatedNamespace, 0, -1);
    }
    return \substr($this->terminatedNamespace, $pos + 1, -1);
  }

  /**
   * Gets the last part of the namespace, without trailing separator.
   *
   * @return string|null
   *   Last namespace fragment, or NULL if this is the root namespace.
   */
  public function getTerminatedShortname(): ?string {
    if ($this->terminatedNamespace === '') {
      // This is the root namespace.
      return null;
    }
    if (false === $pos = strrpos($this->terminatedNamespace, '\\', -2)) {
      // This is a one-level namespace.
      return $this->terminatedNamespace;
    }
    return \substr($this->terminatedNamespace, $pos + 1);
  }

  /**
   * Gets the last part of the namespace, without trailing separator.
   *
   * @return string|null
   *   Last namespace fragment, or NULL if this is the root namespace.
   */
  public function getShortFqn(): ?string {
    if ($this->terminatedNamespace === '') {
      // This is the root namespace.
      return '';
    }
    if (false === $pos = strrpos($this->terminatedNamespace, '\\', -2)) {
      // This is a one-level namespace.
      return '\\' . \substr($this->terminatedNamespace, 0, -1);
    }
    return \substr($this->terminatedNamespace, $pos, -1);
  }

  /**
   * @return string
   */
  public function getTerminatedNamespace(): string {
    return $this->terminatedNamespace;
  }

  /**
   * @return string
   */
  public function getFqn(): string {
    if ($this->terminatedNamespace === '') {
      return '';
    }
    return '\\' . rtrim($this->terminatedNamespace, '\\');
  }

  /**
   * @return string
   */
  public function getDirectory(): string {
    return $this->directory;
  }

  /**
   * Gets the directory with a trailing separator.
   *
   * @return string
   *   Directory with trailing separator.
   */
  public function getTerminatedPath(): string {
    // The directory can't be '/'.
    return $this->directory . '/';
  }

  /**
   * Gets the package directory.
   *
   * @param string $subdir
   *   PSR-4 base path relative to the package directory.
   *   Typically '' or '/src'.
   * @param int $level
   *   Number of namespace parts of the package namespace.
   *
   * @return string
   *   Package directory with no trailing separator.
   */
  public function getPackageDirectory(string $subdir = '/src', int $level = 2): string {
    $relativeFqn = $this->getRelativeFqn($level);
    $relativePath = $subdir . str_replace('\\', '/', $relativeFqn);
    if (!str_ends_with($this->directory, $relativePath)) {
      throw new \RuntimeException(sprintf(
        'Directory %s is expected to end with %s.',
        $this->directory,
        $relativePath,
      ));
    }
    return \substr($this->directory, 0, -\strlen($relativePath));
  }

  /**
   * Gets a relative namespace with trailing separator.
   *
   * @param non-negative-int $level
   *   Number of namespace parts of the package namespace.
   *
   * @return string
   *   Relative namespace with trailing separator.
   */
  public function getRelativeTerminatedNamespace(int $level = 2): string {
    if ($level === 0) {
      return $this->terminatedNamespace;
    }
    \assert($level > 0);
    $parts = explode('\\', $this->terminatedNamespace);
    if (count($parts) <= $level) {
      throw new \RuntimeException(sprintf(
        'Namespace %s is too short to get a relative namespace.',
        $this->terminatedNamespace,
      ));
    }
    return implode('\\', \array_slice($parts, $level));
  }

  /**
   * Gets a relative namespace with leading separator.
   *
   * @param int $level
   *   Number of namespace parts of the package namespace.
   *
   * @return string
   *   Relative namespace with trailing separator.
   */
  public function getRelativeFqn(int $level = 2): string {
    if ($level === 0) {
      return $this->getFqn();
    }
    \assert($level > 0);
    $parts = explode('\\', $this->terminatedNamespace);
    $depth = count($parts) - 1;
    if ($depth < $level) {
      throw new \RuntimeException(sprintf(
        'Namespace %s is too short to get a relative namespace.',
        $this->terminatedNamespace,
      ));
    }
    if ($depth === $level) {
      return '';
    }
    return '\\' . implode('\\', \array_slice($parts, $level, -1));
  }

  /**
   * Gets the path relative to the package directory, with leading '/'.
   *
   * @param string $subdir
   *   PSR-4 base path relative to the package directory.
   *   Typically '' or '/src'.
   * @param int $level
   *   Number of namespace parts of the package namespace.
   *
   * @return string
   *   Relative path with leading '/' separator, unless empty.
   */
  public function getRelativePath(string $subdir = '/src', int $level = 2): string {
    $relativeFqn = $this->getRelativeFqn($level);
    $relativePath = $subdir . str_replace('\\', '/', $relativeFqn);
    if (!\str_ends_with($this->directory, $relativePath)) {
      throw new \RuntimeException(sprintf(
        'Directory %s is expected to end with %s.',
        $this->directory,
        $relativePath,
      ));
    }
    return $relativePath;
  }

  /**
   * Gets the path relative to the package directory, with trailing '/'.
   *
   * @param string $subdir
   *   Terminated PSR-4 base path relative to the terminated package directory.
   *   Typically '' or 'src/'.
   * @param int $level
   *   Number of namespace parts of the package namespace.
   *
   * @return string
   *   Relative path with trailing '/' separator, unless empty.
   */
  public function getRelativeTerminatedPath(string $subdir = 'src/', int $level = 2): string {
    $relativeTerminatedNamespace = $this->getRelativeTerminatedNamespace($level);
    $relativeTerminatedPath = $subdir . str_replace('\\', '/', $relativeTerminatedNamespace);
    if (!\str_ends_with($this->directory . '/', $relativeTerminatedPath)) {
      throw new \RuntimeException(sprintf(
        'Directory %s is expected to end with %s.',
        $this->directory . '/',
        $relativeTerminatedPath,
      ));
    }
    return $relativeTerminatedPath;
  }

  /**
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  public function getIterator(): \Iterator {
    return self::scan($this->directory, $this->terminatedNamespace);
  }

  /**
   * @return array{array<string, class-string>, array<string, static>}
   */
  public function getElements(): array {
    $classes = [];
    $subdirs = [];
    foreach (\scandir($this->directory, \SCANDIR_SORT_ASCENDING) as $candidate) {
      if (!preg_match(self::CANDIDATE_REGEX, $candidate, $m)) {
        continue;
      }
      [, $name, $ext] = $m;
      $path = $this->directory . '/' . $candidate;
      if ($ext) {
        if (is_file($path)) {
          $classes[$path] = $this->terminatedNamespace . $name;
        }
      }
      else {
        if (is_dir($path)) {
          $subdirs[$candidate] = $this->subdir($candidate);
        }
      }
    }
    /** @var array<string, class-string> $classes */
    return [$classes, $subdirs];
  }

  /**
   * @return array<string, class-string>
   */
  public function getClassFilesHere(): array {
    $classFiles = [];
    foreach (\scandir($this->directory, \SCANDIR_SORT_ASCENDING) as $candidate) {
      if ('.' === $candidate[0]
        || !\str_ends_with($candidate, '.php')
      ) {
        continue;
      }
      $path = $this->directory . '/' . $candidate;
      if (!\is_file($path)) {
        continue;
      }
      $name = \substr($candidate, 0, -4);
      if (!\preg_match(self::CLASS_NAME_REGEX, $name)) {
        continue;
      }
      $classFiles[$path] = $this->terminatedNamespace . $name;
    }
    /** @var array<string, class-string> $classFiles */
    return $classFiles;
  }

  /**
   * @return \Iterator<string, static>
   */
  public function getSubdirsHere(): \Iterator {
    foreach (\scandir($this->directory, \SCANDIR_SORT_ASCENDING) as $candidate) {
      if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
        continue;
      }
      $path = $this->directory . '/' . $candidate;
      if (!\is_dir($path)) {
        continue;
      }
      yield $candidate => $this->subdir($candidate);
    }
  }

  /**
   * @param string $dir
   * @param string $terminatedNamespace
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  private static function scan(string $dir, string $terminatedNamespace): \Iterator {
    foreach (\scandir($dir, \SCANDIR_SORT_ASCENDING) as $candidate) {
      if ('.' === $candidate[0]) {
        continue;
      }
      $path = $dir . '/' . $candidate;
      if (\str_ends_with($candidate, '.php')) {
        if (!is_file($path)) {
          continue;
        }
        $name = substr($candidate, 0, -4);
        if (!preg_match(self::CLASS_NAME_REGEX, $name)) {
          continue;
        }
        // @phpstan-ignore generator.valueType
        yield $path => $terminatedNamespace . $name;
      }
      else {
        if (!is_dir($path)) {
          continue;
        }
        if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
          continue;
        }
        yield from self::scan(
          $path,
          $terminatedNamespace . $candidate . '\\',
        );
      }
    }
  }

  /**
   * Wraps the current object as a reflection class iterator.
   *
   * This is a convenience method for easier chaining.
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   *   Reflection class iterator.
   */
  public function getReflectionClassesIA(): ReflectionClassesIAInterface {
    return new ReflectionClassesIA_ClassFilesIA($this);
  }


}
