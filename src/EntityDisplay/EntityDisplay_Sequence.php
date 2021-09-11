<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;

/**
 * A sequence of entity display handlers, whose results are assembled into a
 * single render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
class EntityDisplay_Sequence extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  protected $displayHandlers;

  /**
   * @CfrPlugin("sequence", @t("Sequence of entity displays"))
   *
   * @param \Donquixote\Ock\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(CfContextInterface $context = NULL): FormulaInterface {
    return Formula_ValueToValue_CallbackMono::fromClass(
      __CLASS__,
      Formula_IfaceWithContext::createSequence(
        EntityDisplayInterface::class,
        $context));
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displayHandlers
   */
  public function __construct(array $displayHandlers) {

    foreach ($displayHandlers as $delta => $displayHandler) {
      if (!$displayHandler instanceof EntityDisplayInterface) {
        unset($displayHandlers[$delta]);
        break;
      }
    }

    $this->displayHandlers = $displayHandlers;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  public function buildEntities(array $entities): array {

    $builds = [];
    foreach ($this->displayHandlers as $name => $handler) {
      foreach ($handler->buildEntities($entities) as $delta => $entity_build) {
        unset($entity_build['#weight']);
        $builds[$delta][$name] = $entity_build;
      }
    }

    return $builds;
  }
}
