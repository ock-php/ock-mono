<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\ServiceProxy;

use Donquixote\DID\Exception\ContainerToValueException;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaException;
use Ock\Egg\Egg\EggInterface;
use Psr\Container\ContainerInterface;

class Formula_ContainerProxy_Egg implements Formula_ContainerProxyInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Egg\Egg\EggInterface<FormulaInterface> $formulaEgg
   */
  public function __construct(
    private readonly EggInterface $formulaEgg,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function containerGetFormula(ContainerInterface $container): FormulaInterface {
    try {
      $candidate = $this->formulaEgg->hatch($container);
    }
    catch (ContainerToValueException $e) {
      throw new FormulaException($e->getMessage(), 0, $e);
    }
    if ($candidate === NULL || $candidate instanceof FormulaInterface) {
      return $candidate;
    }
    throw new FormulaException(sprintf(
      'Expected a formula, found %s',
      MessageUtil::formatValue($candidate),
    ));
  }

}