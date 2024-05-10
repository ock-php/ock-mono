<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Inspector;

use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;

trait AdapterFactoryInspectorTrait {

  /**
   * This property should be set in the constructor.
   */
  protected readonly ParamToCTVInterface $paramToCTV;

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return bool
   *
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
   * @return list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface>
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function buildArgCTVs(array $parameters): array {
    $argCTVs = [];
    foreach ($parameters as $parameter) {
      $ctv = $this->paramToCTV->paramGetCTV($parameter);
      if ($ctv === NULL) {
        throw new DiscoveryException(sprintf(
          'Cannot resolve %s.',
          MessageUtil::formatReflector($parameter),
        ));
      }
      $argCTVs[] = $ctv;
    }
    return $argCTVs;
  }

}
