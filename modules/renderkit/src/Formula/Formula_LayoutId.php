<?php

declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\DrupalText;
use Drupal\service_discovery\Attribute\RequireModule;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Formula\Select\Formula_Select_BufferedBase;
use Ock\Ock\Text\Text;

/**
 * Formula to select a layout id.
 *
 * @todo Only register service if 'layout_discovery' module is installed.
 */
#[Service]
#[RequireModule('layout_discovery')]
class Formula_LayoutId extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   */
  public function __construct(
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
