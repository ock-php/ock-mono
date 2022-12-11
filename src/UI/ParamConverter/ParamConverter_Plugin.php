<?php
declare(strict_types=1);

namespace Drupal\ock\UI\ParamConverter;

use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Plugin\NamedPlugin;

class ParamConverter_Plugin extends ParamConverterBase {

  public const TYPE = 'ock:plugin';

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $plugin_map
   */
  public function __construct(PluginMapInterface $plugin_map) {
    $this->pluginMap = $plugin_map;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $interface = $defaults['interface'] ?? '';
    if ($interface === '') {
      return null;
    }
    if (!is_string($interface)) {
      throw new \RuntimeException('Interface must be a string.');
    }
    try {
      $plugins = $this->pluginMap->typeGetPlugins($interface);
    }
    catch (PluginListException $e) {
      \watchdog_exception('ock', $e);
    }
    $plugin = $plugins[$value] ?? null;
    if (!$plugin) {
      return null;
    }

    return new NamedPlugin($value, $plugin);
  }
}
