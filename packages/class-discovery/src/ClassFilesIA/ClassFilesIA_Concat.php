<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

class ClassFilesIA_Concat implements ClassFilesIAInterface {

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   */
  public function __construct(
    private array $classFilesIAs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classFilesIAs as $classFilesIA) {
      yield from $classFilesIA;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function withRealpathRoot(): static {
    $clone = clone $this;
    foreach ($clone->classFilesIAs as $i => $classFilesIA) {
      $clone->classFilesIAs[$i] = $classFilesIA->withRealpathRoot();
    }
    return $clone;
  }
}
