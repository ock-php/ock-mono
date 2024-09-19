<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\ClassNamesIA;

/**
 * @template-covariant TKey
 *
 * @template-extends \IteratorAggregate<TKey, class-string>
 */
interface ClassNamesIAInterface extends \IteratorAggregate {

  /**
   * Iterates through class names, with arbitrary keys.
   *
   * @return \Iterator<TKey, class-string>
   *   Format: $[*] = $class
   */
  public function getIterator(): \Iterator;

}
