<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Inspector;

use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\Exception\DiscoveryException;
use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Donquixote\Helpers\Util\MessageUtil;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

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
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  private function buildArgCTVs(array $parameters): array {
    $argEggs = [];
    foreach ($parameters as $parameter) {
      $egg = $this->paramToCTV->paramGetCTV($parameter);
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
