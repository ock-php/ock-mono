<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Attribute\Parameter\OckListOfObjects;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * A sequence of entity display handlers, whose results are assembled into a
 * single render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
#[OckPluginInstance('sequence', 'Sequence of entity displays')]
class EntityDisplay_Sequence extends EntitiesDisplayBase {

  /**
   * Constructor.
   *
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
    $elements = [];
    foreach ($this->displays as $name => $handler) {
      foreach ($handler->buildEntities($entities) as $delta => $element) {
        unset($element['#weight']);
        $elements[$delta][$name] = $element;
      }
    }
    return $elements;
  }
}
