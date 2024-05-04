<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Donquixote\Adaptism\Attribute\AdapterAttributeInterface;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Attribute\SelfAdapter;
use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Construct;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_SelfMethod;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\Shared\ReflectionClassesIAHavingBase;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\DID\ClassToCTV\ClassToCTVInterface;
use Donquixote\DID\Exception\DiscoveryException;
use Donquixote\DID\Exception\MalformedDeclarationException;
use Donquixote\DID\ParamToCTV\ParamToCTVInterface;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\DID\Util\ReflectionTypeUtil;

/**
 * The annotated service is an empty definition list.
 */
#[Service(self::class)]
class AdapterDefinitionList_Discovery extends ReflectionClassesIAHavingBase implements AdapterDefinitionListInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\DID\ClassToCTV\ClassToCTVInterface $classToCTV
   * @param \Donquixote\DID\ParamToCTV\ParamToCTVInterface $paramToCTV
   */
  public function __construct(
    #[GetService]
    private readonly ClassToCTVInterface $classToCTV,
    #[GetService]
    private readonly ParamToCTVInterface $paramToCTV,
  ) {}

  /**
   * @param \Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionList_Discovery $emptyDefinitionList
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return self
   */
  #[Service]
  public static function create(
    #[GetService]
    self $emptyDefinitionList,
    #[GetService(serviceIdSuffix: self::class)]
    ClassFilesIAInterface $classFilesIA,
  ): self {
    return $emptyDefinitionList->withClassFilesIA($classFilesIA);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    try {
      return $this->discoverDefinitions();
    }
    catch (DiscoveryException $e) {
      throw new MalformedAdapterDeclarationException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface[]
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function discoverDefinitions(): array {
    $definitions = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->itReflectionClasses() as $reflectionClass) {
      $adapterAttribute = AttributesUtil::getSingle($reflectionClass, AdapterAttributeInterface::class);
      if ($adapterAttribute !== null) {
        $definitions[$reflectionClass->getName()] = $this->onClass(
          $adapterAttribute,
          $reflectionClass,
        );
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        $adapterAttribute = AttributesUtil::getSingle($reflectionMethod, AdapterAttributeInterface::class);
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
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onClass(
    AdapterAttributeInterface $attribute,
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
    $argCTVs = $this->buildArgCTVs($parameters);
    $adapterCTV = SpecificAdapter_Construct::ctv(
      $class,
      $hasUniversalAdapterParameter,
      $argCTVs,
    );
    return new AdapterDefinition_Simple(
      $sourceType,
      $class,
      $attribute->getSpecifity() ?? $specifity,
      $adapterCTV,
    );
  }

  /**
   * @param \Donquixote\Adaptism\Attribute\AdapterAttributeInterface $attribute
   * @param \ReflectionClass $reflectionClass
   * @param \ReflectionMethod $reflectionMethod
   *
   * @return \Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  private function onMethod(
    AdapterAttributeInterface $attribute,
    \ReflectionClass $reflectionClass,
    \ReflectionMethod $reflectionMethod,
  ): AdapterDefinitionInterface {
    if (!$reflectionMethod->isPublic()) {
      throw new MalformedDeclarationException(\sprintf(
        'Method %s must be public.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    if ($reflectionMethod->isConstructor()) {
      // @todo Allow adapter attribute on the constructor?
      throw new MalformedDeclarationException(\sprintf(
        'Method %s is a constructor, and cannot have an adapter attribute. Put the attribute on the class instead.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $class = $reflectionClass->getName();
    $method = $reflectionMethod->getName();
    $where = $class . '::' . $method . '()';
    $parameters = $reflectionMethod->getParameters();
    if (!$attribute->isSelfAdapter()) {
      $sourceType = $this->extractSourceType($parameters, $specifity, $where);
    }
    else {
      $sourceType = $reflectionClass->getName();
    }
    $hasResultTypeParameter = $this->extractHasResultTypeParameter($parameters);
    $hasUniversalAdapterParameter = $this->extractHasUniversalAdapterParameter($parameters);
    $moreArgCTVs = $this->buildArgCTVs($parameters);
    if ($reflectionMethod->isStatic()) {
      if ($attribute instanceof SelfAdapter) {
        throw new MalformedDeclarationException(\sprintf(
          'Self-adapter method cannot be static: %s.',
          MessageUtil::formatReflector($reflectionMethod),
        ));
      }
      $adapterCTV = SpecificAdapter_Callback::ctvMethodCall(
        $class,
        $method,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $moreArgCTVs,
      );
    }
    elseif ($attribute instanceof SelfAdapter) {
      $adapterCTV = SpecificAdapter_SelfMethod::ctv(
        $class,
        $method,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $moreArgCTVs,
      );
    }
    else {
      // Method is not static, and is not a self-adapter.
      $adapterCTV = SpecificAdapter_Callback::ctvMethodCall(
        $this->classToCTV->classGetCTV($reflectionClass),
        $method,
        $hasResultTypeParameter,
        $hasUniversalAdapterParameter,
        $moreArgCTVs,
      );
    }
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflectionMethod, true);
    return new AdapterDefinition_Simple(
      $sourceType,
      $returnClass,
      $attribute->getSpecifity()
        ?? \count($reflectionClass->getInterfaceNames()),
      $adapterCTV,
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
   * @throws \Donquixote\DID\Exception\MalformedDeclarationException
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
    returN $argCTVs;
  }

}
