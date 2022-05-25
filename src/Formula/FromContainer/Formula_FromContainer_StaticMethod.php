<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Formula\FromContainer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Psr\Container\ContainerInterface;

class Formula_FromContainer_StaticMethod implements Formula_FromContainerInterface {

  /**
   * @var callable
   */
  private $factory;

  /**
   * Constructor.
   *
   * @param callable $factory
   * @param string[] $ids
   *   Ids of services to pass as parameters.
   */
  public function __construct(
    callable $factory,
    private readonly array $ids,
  ) {
    $this->factory = $factory;
  }

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   * @throws \Psr\Container\NotFoundExceptionInterface
   */
  public function getRealFormula(ContainerInterface $container): FormulaInterface {
    $args = [];
    foreach ($this->ids as $id) {
      $args[] = $container->get($id);
    }
    return ($this->factory)(...$args);
  }

}
