<?php

namespace Drupal\renderkit8\EntityDisplay\Switcher;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

/**
 * Chain-of-responsibility entity display switcher.
 */
class EntityDisplay_ChainOfResponsibility extends EntitiesDisplayBase {

  /**
   * The displays to try.
   *
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[] = $displayHandler
   */
  private $displays;

  /**
   * @CfrPlugin(
   *   id = "chain",
   *   label = "Chain of responsibility"
   * )
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createCfrSchema(CfContextInterface $context = NULL) {

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [CfSchema_IfaceWithContext::createSequence(
        EntityDisplayInterface::class,
        $context)],
      ['']);
  }

  /**
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[] $displays
   */
  public function __construct(array $displays) {
    $this->displays = $displays;
  }

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  public function buildEntities($entityType, array $entities) {
    $builds = array_fill_keys(array_keys($entities), NULL);
    foreach ($this->displays as $display) {
      foreach ($display->buildEntities($entityType, $entities) as $delta => $build) {
        if (!empty($build)) {
          if (empty($builds[$delta])) {
            $builds[$delta] = $build;
          }
          unset($entities[$delta]);
        }
      }
      /** @noinspection DisconnectedForeachInstructionInspection */
      if ([] === $entities) {
        break;
      }
    }
    return array_filter($builds);
  }

}
