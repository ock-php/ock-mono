<?php
declare(strict_types=1);

namespace Drupal\cu\ParamConverter;

use Donquixote\ObCK\Plugin\Map\PluginMapInterface;
use Donquixote\ObCK\Plugin\NamedPlugin;

class ParamConverter_Plugin extends ParamConverterBase {

  public const TYPE = 'cu:plugin';

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $plugin_map
   */
  public function __construct(PluginMapInterface $plugin_map) {
    $this->pluginMap = $plugin_map;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {

    if (empty($defaults['interface'])) {
      return FALSE;
    }

    $interface = $defaults['interface'];

    $plugins = $this->pluginMap->typeGetPlugins($interface);

    $plugin = $plugins[$value] ?? NULL;

    if (!$plugin) {
      return FALSE;
    }

    return new NamedPlugin($value, $plugin);
  }
}
