<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Donquixote\Ock\Attribute\Parameter\OckListOfObjects;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
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
