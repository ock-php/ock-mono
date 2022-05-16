<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\TextInterface;

class Formula_Select_FromPlugins extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\Ock\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * @var \Donquixote\Ock\Text\TextInterface|\Donquixote\Ock\Text\TextInterface[]
   */
  private array $groupLabels;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Plugin[] $plugins
   * @param \Donquixote\Ock\Text\TextInterface[] $groupLabels
   */
  public function __construct(array $plugins, array $groupLabels = []) {
    Plugin::validate(...array_values($plugins));
    $this->plugins = $plugins;
    $this->groupLabels = $groupLabels;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    $sortme = array_keys($this->groupLabels + $this->plugins);
    sort($sortme);
    $group_id = '';
    foreach ($sortme as $id) {
      if (isset($this->groupLabels[$id])) {
        $group_id = $id;
      }
      if (isset($this->plugins[$id])) {
        $grouped_options[$group_id][$id] = $this->plugins[$id]->getLabel();
      }
    }
    $group_labels = array_intersect_key($this->groupLabels, $grouped_options);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    if (!isset($this->plugins[$id])) {
      return NULL;
    }
    return $this->plugins[$id]->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return isset($this->options[$id]);
  }

}
