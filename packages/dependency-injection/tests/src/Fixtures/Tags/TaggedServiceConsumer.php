<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\Tags;

use Ock\DependencyInjection\Attribute\Service;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[Service]
class TaggedServiceConsumer {

  public array $services;

  public function __construct(
    #[TaggedIterator('sunny')]
    iterable $services,
  ) {
    $this->services = \iterator_to_array($services, false);
  }

}
