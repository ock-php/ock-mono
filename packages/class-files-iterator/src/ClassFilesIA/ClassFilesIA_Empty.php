<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

class ClassFilesIA_Empty implements ClassFilesIAInterface {

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return new \EmptyIterator();
  }

}
