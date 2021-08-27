<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Plugin\Map\PluginMapInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Select_FromPluginMap extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $pluginMap;

  /**
   * @var string
   */
  private string $type;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $pluginMap
   * @param string $type
   */
  public function __construct(PluginMapInterface $pluginMap, string $type) {
    $this->pluginMap = $pluginMap;
    $this->type = $type;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->pluginMap->typeGetPlugins($this->type) as $id => $plugin) {
      $label = $plugin->getLabel();
      // @todo Do something for the group label.
      $grouped_options[''][$id] = $label;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    if (!isset($this->plugins[$id])) {
      return NULL;
    }
    return $this->plugins[$id]->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->options[$id]);
  }

}
