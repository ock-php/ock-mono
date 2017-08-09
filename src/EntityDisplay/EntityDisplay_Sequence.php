<?php

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;

/**
 * A sequence of entity display handlers, whose results are assembled into a
 * single render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
class EntityDisplay_Sequence extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[]
   */
  protected $displayHandlers;

  /**
   * @CfrPlugin("sequence", @t("Sequence of entity displays"))
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema(CfContextInterface $context = NULL) {
    return CfSchema_ValueToValue_CallbackMono::fromClass(
      __CLASS__,
      CfSchema_IfaceWithContext::createSequence(
        EntityDisplayInterface::class,
        $context));
  }

  /**
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[] $displayHandlers
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
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {

    $builds = [];
    foreach ($this->displayHandlers as $name => $handler) {
      foreach ($handler->buildEntities($entityType, $entities) as $delta => $entity_build) {
        unset($entity_build['#weight']);
        $builds[$delta][$name] = $entity_build;
      }
    }

    return $builds;
  }
}
