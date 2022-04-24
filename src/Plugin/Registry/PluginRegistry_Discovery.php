<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\Ock\Attribute\Plugin\PluginAttributeInterface;
use Donquixote\Ock\Attribute\PluginModifier\PluginModifierAttributeInterface;

class PluginRegistry_Discovery implements PluginRegistryInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   */
  public function __construct(
    private ReflectionClassesIAInterface $reflectionClassesIA,
  ) {}

  /**
   * Static factory.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return self
   */
  public static function fromClassFilesIA(ClassFilesIAInterface $classFilesIA): self {
    return new self(
      new ReflectionClassesIA_ClassFilesIA($classFilesIA));
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    /** @var \ReflectionClass $reflectionClass */
    foreach ($this->reflectionClassesIA as $reflectionClass) {
      $this->collectPlugins($pluginss, $reflectionClass);
      foreach ($reflectionClass->getMethods() as $reflectionMethod) {
        $this->collectPlugins($pluginss, $reflectionMethod);
      }
    }
    return $pluginss;
  }

  /**
   * @param array<string, array<string, \Donquixote\Ock\Plugin\Plugin>>
   *   $pluginss
   * @param \ReflectionClass|\ReflectionMethod $reflector
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException
   */
  private function collectPlugins(array &$pluginss, \ReflectionClass|\ReflectionMethod $reflector): void {
    $provider = AttributesUtil::getSingle($reflector, PluginAttributeInterface::class);
    if ($provider === null) {
      return;
    }
    $declaration = ($reflector instanceof \ReflectionClass)
      ? $provider->fromClass($reflector)
      : $provider->fromMethod($reflector);
    /**
     * @var PluginModifierAttributeInterface $modifier
     * @psalm-ignore-var
     */
    foreach (AttributesUtil::getInstances($reflector, PluginModifierAttributeInterface::class) as $modifier) {
      $declaration = $modifier->modifyPlugin($declaration);
    }
    foreach ($declaration->getTypes() as $type) {
      $pluginss[$type][$declaration->getId()] = $declaration->getPlugin();
    }
  }

}
