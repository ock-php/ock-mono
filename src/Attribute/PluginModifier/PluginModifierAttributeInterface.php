<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\PluginModifier;

use Donquixote\Ock\Plugin\NamedTypedPlugin;

interface PluginModifierAttributeInterface {

  /**
   * @param \Donquixote\Ock\Plugin\NamedTypedPlugin $plugin
   *
   * @return \Donquixote\Ock\Plugin\NamedTypedPlugin
   */
  public function modifyPlugin(NamedTypedPlugin $plugin): NamedTypedPlugin;

}
