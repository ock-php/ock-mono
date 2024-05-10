<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Inspector;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Construct;
use Donquixote\Helpers\Util\MessageUtil;
use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;
use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Donquixote\ClassDiscovery\Reflection\MethodReflection;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Donquixote\DID\ClassToCTV\ClassToCTVInterface;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
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
   * @param \Donquixote\DID\ClassToCTV\ClassToCTVInterface $classToCTV
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    private readonly ClassToCTVInterface $classToCTV,
    protected readonly ParamToCTVInterface $paramToCTV,
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
    $argCTVs = $this->buildArgCTVs($parameters);
    if ($reflector instanceof ClassReflection) {
      $adapterCTV = SpecificAdapter_Construct::ctv(
        $reflector->name,
        $hasUniversalAdapterParameter,
        $argCTVs,
      );
    }
    elseif ($reflector instanceof MethodReflection) {
      if ($reflector->isStatic()) {
        $classOrObjectCTV = $reflector->originalClass;
      }
      else {
        $classOrObjectCTV = $this->classToCTV->classGetCTV($reflector->getClass());
      }
      $adapterCTV = SpecificAdapter_Callback::ctvMethodCall(
        $classOrObjectCTV,
        $reflector->name,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $argCTVs,
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
      $adapterCTV,
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
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
