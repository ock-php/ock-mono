<?php

namespace Donquixote\ClassDiscovery;

final class NamespaceDirectory {

  /**
   * @var string
   */
  private $directory;

  /**
   * @var string
   */
  private $terminatedNamespace;

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return \Donquixote\ClassDiscovery\NamespaceDirectory
   */
  public static function create($directory, $namespace) {

    NsDirUtil::requireUnslashedDirectory($directory);

    $namespace = NsDirUtil::terminateNamespace($namespace);

    return new self($directory, $namespace);
  }

  /**
   * @param string $class
   *
   * @return \Donquixote\ClassDiscovery\NamespaceDirectory
   */
  public static function createFromClass($class) {

    $reflClass = new \ReflectionClass($class);

    return self::create(
      dirname($reflClass->getFileName()),
      $reflClass->getNamespaceName());
  }

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  private function __construct($directory, $terminatedNamespace) {

    $this->directory = $directory;
    $this->terminatedNamespace = $terminatedNamespace;
  }

  /**
   * @return self
   */
  public function withRealpath() {
    return new self(
      realpath($this->directory),
      $this->terminatedNamespace);
  }

  /**
   * @param string $namespace
   *
   * @return self|null
   */
  public function findNamespace($namespace) {

    $namespace = NsDirUtil::terminateNamespace($namespace);

    if (0 !== strpos($namespace, $this->terminatedNamespace)) {
      return NULL;
    }

    if ($namespace === $this->terminatedNamespace) {
      return $this;
    }

    $l = strlen($this->terminatedNamespace);

    $directory = $this->directory . '/' . str_replace(
        '\\',
        '/',
        substr($namespace, $l, -1));

    return new self($directory, $namespace);
  }

  /**
   * @return self
   */
  public function basedir() {

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
  public function requireParentN($nLevelsUp) {

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
            '!dir' => json_encode($this->getDirectory()),
            '!nsp' => json_encode($this->getNamespace()),
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

    if (0 === $nLevelsUp) {
      return $this;
    }

    if (0 > $nLevelsUp) {
      throw new \InvalidArgumentException('Parameter $nLevelsUp must not be negative.');
    }

    if (null === $parent = $this->parent()) {
      return null;
    }

    return $parent->parentN($nLevelsUp - 1);
  }

  /**
   * @return self|null
   */
  public function parent() {

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

    if ('\\' . $subdirName . '\\' !== substr($this->terminatedNamespace, -$l - 2)) {
      return NULL;
    }

    return new self(
      $parentDir,
      substr($this->terminatedNamespace, 0, -($l + 1)));
  }

  /**
   * @param string $fragment
   *
   * @return self
   */
  public function subdir($fragment) {
    return new self(
      $this->directory . '/' . $fragment,
      $this->terminatedNamespace . '\\' . $fragment);
  }

  /**
   * @return string
   */
  public function getNamespace() {
    return rtrim($this->terminatedNamespace);
  }

  /**
   * @return string
   */
  public function getTerminatedNamespace() {
    return $this->terminatedNamespace;
  }

  /**
   * @return string
   */
  public function getDirectory() {
    return $this->directory;
  }
}
