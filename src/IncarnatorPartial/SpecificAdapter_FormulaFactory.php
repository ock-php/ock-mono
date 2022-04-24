<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class SpecificAdapter_FormulaFactory {

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function adapt(
    #[Adaptee] Formula_FormulaFactoryInterface $formula,
    #[GetService] ContainerInterface $container,
  ): ?FormulaInterface {
    try {
      $factory = $formula->getFormulaFactory();
    }
    catch (\Exception $e) {
      throw new AdapterException($e->getMessage(), 0, $e);
    }

    $args = [];
    foreach ($factory->getReflectionParameters() as $param) {
      $id = AttributesUtil::requireGetSingle($param, GetService::class)
          ->getId()
        ?? ReflectionTypeUtil::requireGetClassLikeType($param);
      try {
        $args[] = $container->get($id);
      }
      catch (ContainerExceptionInterface $e) {
        throw new AdapterException($e->getMessage(), 0, $e);
      }
    }

    try {
      $candidate = $factory->invokeArgs($args);
    }
    catch (\Exception $e) {
      throw new AdapterException($e->getMessage(), 0, $e);
    }

    if ($candidate === NULL) {
      return NULL;
    }

    if (!$candidate instanceof FormulaInterface) {
      throw new AdapterException('Expected a formula.');
    }

    return $candidate;
  }

}
