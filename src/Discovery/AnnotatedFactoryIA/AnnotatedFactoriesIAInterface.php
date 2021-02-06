<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\AnnotatedFactoryIA;

interface AnnotatedFactoriesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator|\Donquixote\OCUI\Discovery\AnnotatedFactory\AnnotatedFactory[]
   */
  public function getIterator(): \Iterator;
}
