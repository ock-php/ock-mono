<?php

declare(strict_types=1);

namespace Donquixote\Ock\Inspector;

use Donquixote\ClassDiscovery\Inspector\ClassAndMethodInspectingBase;
use Donquixote\DID\Util\AttributesUtil;
use Donquixote\Ock\Attribute\Plugin\PluginAttributeInterface;
use Donquixote\Ock\Attribute\PluginModifier\PluginModifierAttributeInterface;
use Donquixote\Ock\OckPackage;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(OckPackage::DISCOVERY_TAG_NAME)]
class OckClassInspector extends ClassAndMethodInspectingBase {

  /**
   * {@inheritdoc}
   */
  public function doFindInClass(\ReflectionClass $reflectionClass): array {
    return $this->collectPlugins($reflectionClass);
  }

  /**
   * {@inheritdoc}
   */
  protected function findInMethod(\ReflectionMethod $reflectionMethod, \ReflectionClass $reflectionClass): array {
    return $this->collectPlugins($reflectionMethod);
  }

  /**
   * {@inheritdoc}
   */
  private function collectPlugins(\ReflectionClass|\ReflectionMethod $reflector): array {
    $provider = AttributesUtil::getSingle($reflector, PluginAttributeInterface::class);
    if ($provider === null) {
      return [];
    }
    $declaration = ($reflector instanceof \ReflectionClass)
      ? $provider->onClass($reflector)
      : $provider->onMethod($reflector);
    /**
     * @var PluginModifierAttributeInterface $modifier
     * @psalm-ignore-var
     */
    foreach (AttributesUtil::getAll($reflector, PluginModifierAttributeInterface::class) as $modifier) {
      $declaration = $modifier->modifyPlugin($declaration);
    }
    return [$declaration];
  }

}
