<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Plugin\Plugin;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;

class Formula_Select_FromPlugins implements Formula_SelectInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Plugin[] $plugins
   */
  public function __construct(array $plugins) {
    self::validatePlugins(...array_values($plugins));
    $this->plugins = $plugins;
  }

  /**
   * @param \Donquixote\OCUI\Plugin\Plugin ...$plugins
   */
  private static function validatePlugins(Plugin ...$plugins): void {}

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(TextToMarkupInterface $textToMarkup): array {
    // Make sure the no-label group is at the top.
    $optionss = ['' => []];
    foreach ($this->plugins as $id => $plugin) {
      $label = $plugin->getLabelOr($id);
      // @todo Do something for the group label.
      $optionss[''][$id] = $label;
    }
    if (!$optionss['']) {
      unset($optionss['']);
    }
    return $optionss;
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
