<?php

declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\DrupalText;

/**
 * Formula to select a layout id.
 */
class Formula_LayoutId extends Formula_Select_BufferedBase {

  /**
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  private LayoutPluginManagerInterface $layoutManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   */
  public function __construct(LayoutPluginManagerInterface $layoutManager) {
    $this->layoutManager = $layoutManager;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->layoutManager->getLayoutOptions() as $group_label => $group_options) {
      $group_label[$group_label] = Text::s($group_label);
      $grouped_options[$group_label] = DrupalText::multiple($group_options);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    try {
      $definition = $this->layoutManager->getDefinition($id, FALSE);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Unexpected exception in plugin manager', 0, $e);
    }
    if (!$definition) {
      return NULL;
    }
    return DrupalText::fromVar($definition->getLabel())
      ?? Text::s($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return $this->layoutManager->hasDefinition($id);
  }

}
