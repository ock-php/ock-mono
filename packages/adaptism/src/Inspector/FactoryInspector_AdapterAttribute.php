<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Inspector;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Construct;
use Donquixote\ClassDiscovery\Exception\MalformedDeclarationException;
use Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;
use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\Reflection\MethodReflection;
use Donquixote\ClassDiscovery\Util\AttributesUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Donquixote\Helpers\Util\MessageUtil;
use Ock\Egg\ClassToEgg\ClassToEggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @template-implements FactoryInspectorInterface<\Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface>
 */
#[AutoconfigureTag(AdaptismPackage::DISCOVERY_TAG_NAME)]
class FactoryInspector_AdapterAttribute implements FactoryInspectorInterface {

  use AdapterFactoryInspectorTrait;

  /**
   * Constructor.
   *
   * @param \Ock\Egg\ClassToEgg\ClassToEggInterface $classToEgg
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface $paramToEgg
   */
  public function __construct(
    private readonly ClassToEggInterface $classToEgg,
    protected readonly ParamToEggInterface $paramToEgg,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attribute = AttributesUtil::getSingle($reflector, Adapter::class);
    if ($attribute === NULL) {
      return;
    }
    if (!$reflector->isCallable()) {
      throw new MalformedDeclarationException(\sprintf(
        $reflector->isClass()
          ? 'Class %s must be instantiable.'
          : 'Method %s must be public, not abstract, and not a constructor.',
        $reflector->getDebugName(),
      ));
    }
    $parameters = $reflector->getParameters();
    $sourceType = $this->extractAdapteeType(
      $parameters,
      $specifity,
      $reflector->getDebugName(),
    );
    $hasResultTypeParameter = $reflector->isMethod()
      && $this->extractHasResultTypeParameter($parameters);
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    $argEggs = $this->buildArgCTVs($parameters);
    if ($reflector instanceof ClassReflection) {
      $adapterEgg = SpecificAdapter_Construct::ctv(
        $reflector->name,
        $hasUniversalAdapterParameter,
        $argEggs,
      );
    }
    elseif ($reflector instanceof MethodReflection) {
      if ($reflector->isStatic()) {
        $classOrEgg = $reflector->originalClass;
      }
      else {
        $classOrEgg = $this->classToEgg->classGetCTV($reflector->getClass());
      }
      $adapterEgg = SpecificAdapter_Callback::ctvMethodCall(
        $classOrEgg,
        $reflector->name,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $argEggs,
      );
    }
    else {
      return;
    }
    $resultClass = ReflectionTypeUtil::requireGetClassLikeType($reflector, true);

    $definition = new AdapterDefinition_Simple(
      $sourceType,
      $resultClass,
      $attribute->specifity ?? $specifity,
      $adapterEgg,
    );
    yield $reflector->getFullName() => $definition;
  }

  /**
   * @param \ReflectionParameter[] $parameters
   * @param int|null $specifity
   * @param-out int $specifity
   * @param string $where
   *
   * @return string|null
   *
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
   */
  private function extractAdapteeType(array &$parameters, ?int &$specifity, string $where): ?string {
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
    $adapteeClassName = ReflectionTypeUtil::requireGetClassLikeType($parameter, true);
    if ($adapteeClassName === null) {
      // The type is 'object'.
      $specifity = -1;
      return null;
    }
    try {
      $adapteeReflectionClass = new \ReflectionClass($adapteeClassName);
    }
    // PhpStan assumes that a 'class-string' is already verified to exist.
    // @phpstan-ignore-next-line
    catch (\ReflectionException $e) {
      throw new MalformedDeclarationException(\sprintf(
        'Unknown type on %s: %s',
        MessageUtil::formatReflector($parameter),
        $e->getMessage(),
      ));
    }
    $specifity = \count($adapteeReflectionClass->getInterfaceNames());
    return $adapteeClassName;
  }

}
