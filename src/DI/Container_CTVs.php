<?php

declare(strict_types = 1);

namespace Donquixote\Adaptism\DI;

use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

class Container_CTVs implements ContainerInterface {

  /**
   * @var array
   */
  private array $cache = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ContainerToValue\ContainerToValueInterface[] $ctvs
   */
  public function __construct(
    private readonly array $ctvs,
  ) {}

  /**
   * @template T
   *
   * @param class-string<T>|string $id
   *
   * @return T|mixed
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function get(string $id): mixed {
    return $this->cache[$id]
      ??= ($this->ctvs[$id] ?? $this->fail($id))->containerGetValue($this);
  }

  /**
   * @param string $id
   *
   * @return never
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  private function fail(string $id): never {
    throw new ContainerToValueException(sprintf(
      'Cannot retrieve service %s.',
      $id,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function has(string $id): bool {
    return isset($this->ctvs[$id]);
  }

}
