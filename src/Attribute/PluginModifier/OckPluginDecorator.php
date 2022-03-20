<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\PluginModifier;

use Donquixote\Ock\Attribute\PluginModifier\PluginModifierAttributeInterface;
use Donquixote\Ock\Plugin\NamedTypedPlugin;
use Donquixote\Ock\Plugin\Plugin;

/**
 * Marks the annotated plugin as a decorator.
 *
 * This should be used in combination with another attribute to declare the
 * actual plugin.
 *
 * @see \Donquixote\Ock\Attribute\Plugin\PluginAttributeInterface
 *   Use one of these to declare the actual plugin.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class OckPluginDecorator implements PluginModifierAttributeInterface {

  public function modifyPlugin(NamedTypedPlugin $plugin): NamedTypedPlugin {
    $types = [];
    foreach ($plugin->getTypes() as $type) {
      $types[] = "decorator<$type>";
    }
    return $plugin->withTypes($types);
  }

}
