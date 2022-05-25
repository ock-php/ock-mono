<?php

declare(strict_types=1);

namespace Donquixote\Ock\Adapter;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FromContainer\Formula_FromContainer_StaticMethod;
use Psr\Container\ContainerInterface;

class SpecificAdapter_FromContainer {

  /**
   * @param \Donquixote\Ock\Formula\FromContainer\Formula_FromContainer_StaticMethod $formula
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  #[Adapter]
  public static function adapt(
    Formula_FromContainer_StaticMethod $formula,
    #[GetService] ContainerInterface $container,
  ): FormulaInterface {
    return $formula->getRealFormula($container);
  }

}
