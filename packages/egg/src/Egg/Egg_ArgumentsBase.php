<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

/**
 * Treats the service itself as a callable.
 */
abstract class Egg_ArgumentsBase implements EggInterface {

  /**
   * Constructor.
   *
   * @param (mixed|\Ock\Egg\Egg\EggInterface)[] $args
   */
  public function __construct(
    private readonly array $args,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function hatch(ContainerInterface $container): mixed {
    $args = self::processArray($container, $this->args);
    try {
      return $this->getWithArgs($container, $args);
    }
    catch (\Throwable $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @param \Psr\Container\ContainerInterface $container
   * @param array $in
   *
   * @return array
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  private static function processArray(ContainerInterface $container, array $in): array {
    $out = [];
    foreach ($in as $k => $v) {
      if ($v instanceof EggInterface) {
        $v = $v->hatch($container);
      }
      elseif (is_array($v) && $v) {
        $v = self::processArray($container, $v);
      }
      $out[$k] = $v;
    }
    return $out;
  }

  /**
   * @param \Psr\Container\ContainerInterface $container
   * @param array $args
   *
   * @return mixed
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  abstract protected function getWithArgs(ContainerInterface $container, array $args): mixed;

}
