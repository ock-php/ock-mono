<?php

declare(strict_types = 1);

namespace Donquixote\DID\Container;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Donquixote\DID\ContainerToValue\ContainerToValue_Container;
use Donquixote\DID\CTVList\CTVList_Discovery_ServiceAttribute;
use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\DID\ParamToCTV\ParamToCTV;
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
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Psr\Container\ContainerInterface
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public static function fromClassFilesIAs(array $classFilesIAs): ContainerInterface {
    $containerDiscoveryClassFilesIA = ClassFilesIA::multiple($classFilesIAs);
    $emptyCtvList = new CTVList_Discovery_ServiceAttribute(
      ParamToCTV::create(),
    );
    $ctvs = $emptyCtvList
      ->withClassFilesIA($containerDiscoveryClassFilesIA)
      ->getCTVs();
    $ctvs[ContainerInterface::class] = new ContainerToValue_Container();
    return new Container_CTVs($ctvs);
  }

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