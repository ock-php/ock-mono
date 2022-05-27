<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\AttributesUtil;
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
        $definitions[$reflectionClass->getName()] = $adapterAttribute->onClass($reflectionClass);
      }
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        $adapterAttribute = AttributesUtil::getSingle($reflectionMethod, Adapter::class);
        if ($adapterAttribute) {
          $definitions[$reflectionClass->getName() . '::' . $reflectionMethod->getName()] = $adapterAttribute->onMethod(
            $reflectionClass,
            $reflectionMethod,
          );
        }
      }
    }
    return $definitions;
  }

}
