<?php

declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

/**
 * Formula to select a layout id.
 */
#[RegisterService(modules: ['layout'])]
class Formula_LayoutId extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   */
  public function __construct(
    #[GetService('plugin.manager.core.layout')]
    private readonly LayoutPluginManagerInterface $layoutManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$map, array &$labels, array &$groupLabels): void {
    foreach ($this->layoutManager->getLayoutOptions() as $groupLabel => $optionsInGroup) {
      $groupLabels[$groupLabel] = Text::s($groupLabel);
      // Use + operator to avoid overriding existing options.
      $labels += DrupalText::multiple($optionsInGroup);
      $map += array_fill_keys(array_keys($optionsInGroup), $groupLabel);
    }
  }

}
