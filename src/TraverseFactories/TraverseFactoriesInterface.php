<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\AnnotatedFactories;

use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;
use Donquixote\Ock\FindIn\Factory\FindInAnnotatedFactoryInterface;

interface AnnotatedFactoriesInterface {

  /**
   * @param \Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface $visitor
   *
   * @throws \Exception
   */
  public function accept(FactoryVisitorInterface $visitor): void;

  /**
   * @template TKey
   * @template TValue
   *
   * @param FindInAnnotatedFactoryInterface<TKey, TValue> $findInAnnotatedFactory
   *
   * @return \Iterator|iterable<TKey, TValue>
   *
   * @throws \Exception
   */
  public function find(FindInAnnotatedFactoryInterface $findInAnnotatedFactory): \Iterator;

}
