<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_Callback;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainer_ObjectMethod;
use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface;
use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\Adaptism\Util\NewInstance;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Adaptism\Util\ServiceAttributesUtil;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

class AdapterDefinitionList_Discovery implements AdapterDefinitionListInterface {

  public function __construct(
    private readonly ReflectionClassesIAInterface $reflectionClassesIA,
  ) {}

  public static function fromClassFilesIA(ClassFilesIAInterface $classFilesIA): self {
    return new self(
      new ReflectionClassesIA_ClassFilesIA($classFilesIA),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    try {
      return $this->discoverDefinitions();
    }
    catch (MalformedDeclarationException $e) {
      throw new MalformedAdapterDeclarationException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface[]
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  protected function discoverDefinitions(): array {
    $definitions = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      $adapterAttribute = AttributesUtil::getSingle($reflectionClass, Adapter::class);
      if ($adapterAttribute !== null) {
        $definitions[$reflectionClass->getName()] = $this->onClass(
          $adapterAttribute,
          $reflectionClass,
        );
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        $adapterAttribute = AttributesUtil::getSingle($reflectionMethod, Adapter::class);
        if ($adapterAttribute) {
          $definitions[$reflectionClass->getName() . '::' . $reflectionMethod->getName()] = $this->onMethod(
            $adapterAttribute,
            $reflectionClass,
            $reflectionMethod,
          );
        }
      }
    }
    return $definitions;
  }

  /**
   * @param \Donquixote\Adaptism\Attribute\Adapter $attribute
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private function onClass(
    Adapter $attribute,
    \ReflectionClass $reflectionClass,
  ): AdapterDefinitionInterface {
    $class = $reflectionClass->getName();
    $constructor = $reflectionClass->getConstructor();
    if ($constructor === null) {
      throw new MalformedDeclarationException(\sprintf(
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
      $attribute->getSpecifity() ?? $specifity,
      $factory,
    );
  }

  /**
   * @param \Donquixote\Adaptism\Attribute\Adapter $attribute
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private function onMethod(
    Adapter $attribute,
    \ReflectionClass $reflectionClass,
    \ReflectionMethod $reflectionMethod,
  ): AdapterDefinitionInterface {
    if (!$reflectionMethod->isPublic()) {
      throw new MalformedDeclarationException(\sprintf(
        'Method %s must be public.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
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
        throw new MalformedDeclarationException(\sprintf(
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
      $parameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
      $constructorServiceIds = $parameters
        ? ServiceAttributesUtil::paramsRequireServiceIds($parameters)
        : [];
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
      $attribute->getSpecifity() ?? \count($reflectionClass->getInterfaceNames()),
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
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private function extractSourceType(array &$parameters, ?int &$specifity, string $where): ?string {
    $parameter = \array_shift($parameters);
    if ($parameter === null) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected at least one parameter in %s.',
        $where,
      ));
    }
    if (!AttributesUtil::getSingle($parameter, Adaptee::class)
      && $parameter->getAttributes() !== []
    ) {
      throw new MalformedDeclarationException(\sprintf(
        'Expected either no attribute, or #[Adaptee] attribute, on %s.',
        MessageUtil::formatReflector($parameter),
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
      throw new MalformedDeclarationException(\sprintf(
        'Unknown type on %s: %s',
        MessageUtil::formatReflector($parameter),
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
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
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
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
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
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private function createFactory(
    callable $callback,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $parameters,
  ): AdapterFromContainerInterface {
    $serviceIds = $parameters
      ? ServiceAttributesUtil::paramsRequireServiceIds($parameters)
      : [];
    return new AdapterFromContainer_Callback(
      $callback,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $serviceIds,
    );
  }

}
