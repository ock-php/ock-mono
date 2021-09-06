<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Chain-of-responsibility entity display switcher.
 */
class EntityDisplay_ChainOfResponsibility extends EntitiesDisplayBase {

  /**
   * The displays to try.
   *
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[] = $displayHandler
   */
  private $displays;

  /**
   * @CfrPlugin(
   *   id = "chain",
   *   label = "Chain of responsibility"
   * )
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createCfrFormula(CfContextInterface $context = NULL): FormulaInterface {

    return Formula_GroupVal_Callback::fromClass(
      __CLASS__,
      [Formula_IfaceWithContext::createSequence(
        EntityDisplayInterface::class,
        $context)],
      ['']);
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $displays
   */
  public function __construct(array $displays) {
    $this->displays = $displays;
  }

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
      /** @noinspection DisconnectedForeachInstructionInspection */
      if ([] === $entities) {
        break;
      }
    }
    return array_filter($builds);
  }

}
