<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\Ock\Attribute\Plugin\PluginAttributeInterface;
use Donquixote\Ock\Attribute\PluginModifier\PluginModifierAttributeInterface;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Exception\PluginListException;

class PluginRegistry_Discovery implements PluginRegistryInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   */
  public function __construct(
    private readonly ReflectionClassesIAInterface $reflectionClassesIA,
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
      new ReflectionClassesIA_ClassFilesIA($classFilesIA),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    try {
      /** @var \ReflectionClass $reflectionClass */
      foreach ($this->reflectionClassesIA as $reflectionClass) {
        $this->collectPlugins($pluginss, $reflectionClass);
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
          $this->collectPlugins($pluginss, $reflectionMethod);
        }
      }
    }
    catch (MalformedDeclarationException|FormulaException $e) {
      throw new PluginListException('Bad plugin declarations: ' . $e->getMessage(), 0, $e);
    }
    return $pluginss;
  }

  /**
   * @param array<string, array<string, \Donquixote\Ock\Plugin\Plugin>> $pluginss
   * @param \ReflectionClass|\ReflectionMethod $reflector
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  private function collectPlugins(array &$pluginss, \ReflectionClass|\ReflectionMethod $reflector): void {
    $provider = AttributesUtil::getSingle($reflector, PluginAttributeInterface::class);
    if ($provider === null) {
      return;
    }
    $declaration = ($reflector instanceof \ReflectionClass)
      ? $provider->onClass($reflector)
      : $provider->onMethod($reflector);
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
