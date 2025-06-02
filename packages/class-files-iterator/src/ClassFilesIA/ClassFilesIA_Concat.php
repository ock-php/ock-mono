<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

class ClassFilesIA_Concat implements ClassFilesIAInterface {

  /**
   * @param \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   */
  public function __construct(
    private readonly array $classFilesIAs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classFilesIAs as $classFilesIA) {
      yield from $classFilesIA;
    }
  }

}
