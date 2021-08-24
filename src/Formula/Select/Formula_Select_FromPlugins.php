<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\TextToMarkup\TextToMarkupInterface;

class Formula_Select_FromPlugins extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\ObCK\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Plugin[] $plugins
   */
  public function __construct(array $plugins) {
    self::validatePlugins(...array_values($plugins));
    $this->plugins = $plugins;
  }

  /**
   * @param \Donquixote\ObCK\Plugin\Plugin ...$plugins
   */
  private static function validatePlugins(Plugin ...$plugins): void {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->plugins as $id => $plugin) {
      $label = $plugin->getLabelOr($id);
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
