<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\AnnotatedFactoryIA;

interface AnnotatedFactoriesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator|\Donquixote\ObCK\Discovery\AnnotatedFactory\AnnotatedFactory[]
   */
  public function getIterator(): \Iterator;
}
