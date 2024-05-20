<?php

declare(strict_types=1);

namespace Ock\Adaptism\Inspector;

use Ock\Adaptism\Attribute\Parameter\AdapterTargetType;
use Ock\Adaptism\Attribute\Parameter\UniversalAdapter;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\ClassDiscovery\Exception\DiscoveryException;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Ock\Helpers\Util\MessageUtil;

trait AdapterFactoryInspectorTrait {

  /**
   * This property should be set in the constructor.
   */
  protected readonly ParamToEggInterface $paramToEgg;

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return bool
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  private function extractHasResultTypeParameter(array &$parameters): bool {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      return false;
    }
    if (ReflectionTypeUtil::getBuiltinType($parameter) !== 'string') {
      \array_unshift($parameters, $parameter);
      return false;
    }
    if (!AttributesUtil::hasSingle($parameter, AdapterTargetType::class)) {
      \array_unshift($parameters, $parameter);
      return false;
    }
    return true;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return bool
   *
   * @throws \Ock\ClassDiscovery\Exception\MalformedDeclarationException
   */
  private function extractHasUniversalAdapterParameter(array &$parameters): bool {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      return false;
    }
    if (ReflectionTypeUtil::getClassLikeType($parameter) !== UniversalAdapterInterface::class) {
      \array_unshift($parameters, $parameter);
      return false;
    }
    if (!AttributesUtil::hasSingle($parameter, UniversalAdapter::class)
      && $parameter->getAttributes() !== []
    ) {
      \array_unshift($parameters, $parameter);
      return false;
    }
    ReflectionTypeUtil::requireClassLikeType($parameter, UniversalAdapterInterface::class);
    return true;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return list<\Ock\Egg\Egg\EggInterface>
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildArgEggs(array $parameters): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToEgg->paramGetEgg($parameter);
      if ($egg === NULL) {
        throw new DiscoveryException(sprintf(
          'Cannot resolve %s.',
          MessageUtil::formatReflector($parameter),
        ));
      }
      $argEggs[] = $egg;
    }
    return $argEggs;
  }

}
