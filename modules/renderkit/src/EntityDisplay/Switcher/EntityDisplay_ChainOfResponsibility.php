<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Ock\Ock\Attribute\Parameter\OckListOfObjects;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Chain-of-responsibility entity display switcher.
 */
#[OckPluginInstance('chain', 'Chain of responsibility')]
class EntityDisplay_ChainOfResponsibility extends EntitiesDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displays
   */
  public function __construct(
    #[OckOption('displays', 'Displays')]
    #[OckListOfObjects(EntityDisplayInterface::class)]
    private readonly array $displays,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $builds = array_fill_keys(array_keys($entities), NULL);
    foreach ($this->displays as $display) {
      foreach ($display->buildEntities($entities) as $delta => $build) {
        if (!empty($build)) {
          if (empty($builds[$delta])) {
            $builds[$delta] = $build;
          }
          unset($entities[$delta]);
        }
      }
      if ($entities === []) {
        break;
      }
    }
    return array_filter($builds);
  }

}
