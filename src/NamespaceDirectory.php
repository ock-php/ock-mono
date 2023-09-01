<?php

namespace Donquixote\ClassDiscovery;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

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
   * @param string $directory
   * @param string $namespace
   *
   * @return self
   */
  public static function create($directory, $namespace): self {

    NsDirUtil::requireUnslashedDirectory($directory);

    $namespace = NsDirUtil::terminateNamespace($namespace);

    return new self($directory, $namespace);
  }

  /**
   * @param class-string $class
   *
   * @return self
   * @throws \ReflectionException
   */
  public static function createFromClass(string $class): self {

    $reflClass = new \ReflectionClass($class);

    return self::create(
      dirname($reflClass->getFileName()),
      $reflClass->getNamespaceName());
  }

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  private function __construct(
    private string $directory,
    private string $terminatedNamespace,
  ) {}

  /**
   * @return self
   */
  public function withRealpath() {
    return new self(
      realpath($this->directory),
      $this->terminatedNamespace);
  }

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
      . '/' . str_replace(
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
   * @param int $nLevelsUp
   *
   * @return self
   *
   * @throws \RuntimeException
   */
  public function requireParentN($nLevelsUp): self {

    if (null === $parent = $this->parentN($nLevelsUp)) {
      throw new \RuntimeException("No parent-!n namespace directory found for !dir / !nsp.");
    }

    return $parent;
  }

  /**
   * @return \Donquixote\ClassDiscovery\NamespaceDirectory
   *
   * @throws \RuntimeException
   */
  public function requireParent() {

    if (null === $parent = $this->parent()) {
      throw new \RuntimeException(
        strtr(
          "No parent namespace directory found for !dir / !nsp.",
          [
            '!dir' => var_export($this->getDirectory(), TRUE),
            '!nsp' => var_export($this->getNamespace(), TRUE),
          ]));
    }

    return $parent;
  }

  /**
   * @param int $nLevelsUp
   *
   * @return self|null
   */
  public function parentN($nLevelsUp) {

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
   * @return self|null
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
  public function subdir($fragment) {
    return new self(
      $this->directory . '/' . $fragment,
      $this->terminatedNamespace . $fragment . '\\',
    );
  }

  /**
   * @return string
   */
  public function getNamespace(): string {
    return rtrim($this->terminatedNamespace);
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
  public function getDirectory(): string {
    return $this->directory;
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
          // @todo Make the $candidate available as a variable?
          $classes[$path] = $this->terminatedNamespace . $name;
        }
      }
      else {
        if (is_dir($path)) {
          $subdirs[$candidate] = $this->subdir($candidate);
        }
      }
    }
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
      // @todo Make the $candidate available as a variable?
      $classFiles[$path] = $this->terminatedNamespace . $name;
    }
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
   *
   * @psalm-suppress MoreSpecificReturnType
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

}
