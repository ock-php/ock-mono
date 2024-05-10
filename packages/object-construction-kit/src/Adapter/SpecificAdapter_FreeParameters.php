<?php

declare(strict_types=1);

namespace Donquixote\Ock\Adapter;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParametersInterface;
use Psr\Container\ContainerInterface;

class SpecificAdapter_FreeParameters {

  /**
   * @param \Donquixote\Ock\Formula\FreeParameters\Formula_FreeParametersInterface $formula
   * @param \Psr\Container\ContainerInterface $container
   * @param \Donquixote\DID\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   * @throws \ReflectionException
   * @throws \Exception
   */
  #[Adapter]
  public static function adapt(
    #[Adaptee] Formula_FreeParametersInterface $formula,
    #[GetService] ContainerInterface $container,
    #[GetService] ParamToCTVInterface $paramToCTV,
  ): FormulaInterface {
    $args = [];
    foreach ($formula->getFreeParameters() as $index => $parameter) {
      $ctv = $paramToCTV->paramGetCTV($parameter);
      if ($ctv === NULL) {
        continue;
      }
      $args[$index] = $ctv->containerGetValue($container);
    }
    return $formula->withArgValues($args);
  }

}
