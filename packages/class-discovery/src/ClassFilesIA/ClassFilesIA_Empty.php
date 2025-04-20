<?php

namespace Ock\ClassDiscovery\ClassFilesIA;

class ClassFilesIA_Empty implements ClassFilesIAInterface {

  use RealpathRootThisTrait;

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return new \EmptyIterator();
  }

}
