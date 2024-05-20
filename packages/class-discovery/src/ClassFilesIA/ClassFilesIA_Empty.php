<?php

namespace Ock\ClassDiscovery\ClassFilesIA;

class ClassFilesIA_Empty implements ClassFilesIAInterface {

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return new \EmptyIterator();
  }

  /**
   * {@inheritdoc}
   */
  public function withRealpathRoot(): static {
    return $this;
  }
}
