<?php

declare(strict_types=1);

namespace Donquixote\Ock\Adapter;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\DI\ParamToValueInterface;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\ServiceAttributesUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParametersInterface;
use Psr\Container\ContainerInterface;

class SpecificAdapter_FreeParameters {

  /**
   * @param \Donquixote\Ock\Formula\FreeParameters\Formula_FreeParametersInterface $formula
   * @param \Psr\Container\ContainerInterface $container
   * @param \Donquixote\Adaptism\DI\ParamToValueInterface $paramToValue
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
    #[GetService] ParamToValueInterface $paramToValue,
  ): FormulaInterface {
    $args = [];
    foreach ($formula->getFreeParameters() as $index => $parameter) {
      $args[$index] = $paramToValue->paramGetValue($parameter);
      try {
        $id = ServiceAttributesUtil::paramGetServiceId($parameter);
      }
      catch (MalformedDeclarationException $e) {
        throw new FormulaException('Malformed service attribute on parameter.', 0, $e);
      }
      if ($id !== NULL) {
        $args[$index] = $container->get($id);
      }
    }
    return $formula->withArgValues($args);
  }

}
