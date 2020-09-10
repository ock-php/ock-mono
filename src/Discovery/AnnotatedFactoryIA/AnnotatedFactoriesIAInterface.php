<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\AnnotatedFactoryIA;

interface AnnotatedFactoriesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory\AnnotatedFactory[]
   */
  public function getIterator(): \Iterator;
}
