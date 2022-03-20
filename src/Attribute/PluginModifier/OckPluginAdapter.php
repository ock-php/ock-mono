<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\PluginModifier;

use Donquixote\Ock\Plugin\NamedTypedPlugin;

/**
 * Attribute to
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class OckPluginAdapter implements PluginModifierAttributeInterface {

  /**
   * {@inheritdoc}
   */
  public function modifyPlugin(NamedTypedPlugin $plugin): NamedTypedPlugin {
    // @todo Do something for adapters.
    return $plugin;
  }

}
