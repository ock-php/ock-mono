<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_Callback;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_ObjectMethod;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Exception\AdapterNotAvailableException;
use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\NewInstance;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Adaptism\Util\ReflectionUtil;

/**
 * Marks a class or method as an adapter.
 *
 * If placed on a class, the first parameter of the constructor is considered
 * the adaptee object, and the class instance is considered the adapter.
 *
 * If placed on a method, the first parameter of that method is considered the
 * adaptee, and the return value is considered the adapter.
 *
 * If the method is not static, then an instance will be constructed based on
 * annotated constructor parameters.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class Adapter {

  public function __construct(
    private readonly ?int $specifity = null
  ) {}

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function onClass(\ReflectionClass $reflectionClass): AdapterDefinitionInterface {
    $class = $reflectionClass->getName();
    $constructor = $reflectionClass->getConstructor();
    if ($constructor === null) {
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected a constructor on %s.',
        $reflectionClass->getName(),
      ));
    }
    $parameters = $constructor->getParameters();
    $sourceType = $this->extractSourceType(
      $parameters,
      $specifity,
      $class . '::__construct()',
    );
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    $factory = $this->createFactory(
      [NewInstance::class, $class],
      false,
      $hasUniversalAdapterParameter,
      $parameters,
    );
    return new AdapterDefinition_Simple(
      $sourceType,
      $class,
      $this->specifity ?? $specifity,
      $factory,
    );
  }

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function onMethod(
    \ReflectionClass $reflectionClass,
    \ReflectionMethod $reflectionMethod
  ): AdapterDefinitionInterface {
    $class = $reflectionClass->getName();
    $method = $reflectionMethod->getName();
    $where = $class . '::' . $method . '()';
    $parameters = $reflectionMethod->getParameters();
    $sourceType = $this->extractSourceType($parameters, $specifity, $where);
    $hasResultTypeParameter = $this->extractHasResultTypeParameter($parameters);
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    if ($reflectionMethod->isStatic()) {
      $factory = $this->createFactory(
        [$class, $method],
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $parameters,
      );
    }
    else {
      if ($parameters !== []) {
        throw new MalformedAdapterDeclarationException(\sprintf(
          'Leftover parameters %s on %s.',
          \implode(', ', \array_map(
            static function (\ReflectionParameter $parameter) {
              return '$' . $parameter->getName();
            },
            $parameters,
          )),
          $where,
        ));
      }
      $constructorServiceIds = \array_map(
        [$this, 'extractServiceId'],
        $reflectionClass->getConstructor()?->getParameters() ?? [],
      );
      $factory = new AdapterFromContainer_ObjectMethod(
        [NewInstance::class, $class],
        $method,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $constructorServiceIds,
      );
    }
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflectionMethod, true);
    return new AdapterDefinition_Simple(
      $sourceType,
      $returnClass,
      $this->specifity ?? \count($reflectionClass->getInterfaceNames()),
      $factory,
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   * @param int|null $specifity
   * @param-out int $specifity
   * @param string $where
   *
   * @return string|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  private function extractSourceType(array &$parameters, ?int &$specifity, string $where): ?string {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected at least one parameter in %s.',
        $where,
      ));
    }
    if (!AttributesUtil::getSingle($parameter, Adaptee::class)
      && $parameter->getAttributes() !== []
    ) {
      throw new MalformedAdapterDeclarationException(\sprintf(
        'Expected either no attribute, or #[Adaptee] attribute, on %s.',
        ReflectionUtil::reflectorDebugName($parameter),
      ));
    }
    $type = ReflectionTypeUtil::requireGetClassLikeType($parameter, true);
    if ($type === null) {
      // The type is 'object'.
      $specifity = -1;
      return null;
    }
    try {
      $reflectionClass = new \ReflectionClass($type);
    }
    catch (\ReflectionException $e) {
      throw new AdapterNotAvailableException(\sprintf(
        'Unknown type on %s: %s',
        ReflectionUtil::reflectorDebugName($parameter),
        $e->getMessage(),
      ));
    }
    $specifity = \count($reflectionClass->getInterfaceNames());
    return $type;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return bool
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
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
    if (!AttributesUtil::hasSingle($parameter, AdapterTargetType::class)
      && $parameter->getAttributes() !== []
    ) {
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
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
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
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param array $parameters
   *
   * @return \Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface
   */
  private function createFactory(
    callable $callback,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $parameters,
  ): AdapterFromContainerInterface {
    $serviceIds = $parameters
      ? \array_map([$this, 'extractServiceId'], $parameters)
      : [];
    return new AdapterFromContainer_Callback(
      $callback,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $serviceIds,
    );
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return string
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
   */
  private function extractServiceId(\ReflectionParameter $parameter): string {
    return AttributesUtil::requireGetSingle($parameter, GetService::class)->getId()
      ?? ReflectionTypeUtil::requireGetClassLikeType($parameter);
  }

}
