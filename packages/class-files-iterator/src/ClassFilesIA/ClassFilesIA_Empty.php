<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

class ClassFilesIA_Empty implements ClassFilesIAInterface {

  #[\Override]
  public function getIterator(): \Iterator {
    return new \EmptyIterator();
  }

}
