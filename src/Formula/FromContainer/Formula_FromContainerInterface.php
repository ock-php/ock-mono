<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Formula\FromContainer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Psr\Container\ContainerInterface;

interface Formula_FromContainerInterface extends FormulaInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getRealFormula(ContainerInterface $container): FormulaInterface;

}
