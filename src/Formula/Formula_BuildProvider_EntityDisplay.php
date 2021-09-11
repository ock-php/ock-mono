<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Exception\EvaluatorException;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\IdToFormula\IdToFormula_Class;
use Donquixote\Ock\Zoo\V2V\Group\V2V_GroupInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\renderkit\BuildProvider\BuildProvider_EntityDisplay;
use Drupal\renderkit\EntityDisplay\EntityDisplay;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class Formula_BuildProvider_EntityDisplay implements Formula_GroupInterface, V2V_GroupInterface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldown(): Formula_DrilldownInterface {
    return Formula_Drilldown::create(
      Formula_EntityType_WithGroupLabels::create(),
      new IdToFormula_Class(self::class));
  }

  /**
   * @param string $entityTypeId
   */
  public function __construct($entityTypeId) {
    $this->entityTypeId = $entityTypeId;
  }

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface[]
   *   Format: $[$groupItemKey] = $groupItemFormula
   */
  public function getItemFormulas(): array {
    return [
      'entity_id' => new Formula_EntityId($this->entityTypeId),
      'entity_display' => EntityDisplay::formula($this->entityTypeId),
    ];
  }

  /**
   * @return string[]
   */
  public function getLabels(): array {
    return [
      'entity_id' => t('Entity id'),
      'entity_display' => t('Entity display'),
    ];
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {
    return self::createBuildProvider(
      $this->entityTypeId,
      $values['entity_id'],
      $values['entity_display']);
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return '\\' . self::class . '::createBuildProvider('
      . "\n" . var_export($this->entityTypeId, TRUE)
      . "\n" . $itemsPhp['entity_id']
      . "\n" . $itemsPhp['entity_display'] . ')';

  }

  /**
   * @param string $entityTypeId
   * @param int|string $entityId
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   *
   * @return \Drupal\renderkit\BuildProvider\BuildProvider_EntityDisplay
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   */
  public static function createBuildProvider(
    $entityTypeId,
    $entityId,
    EntityDisplayInterface $entityDisplay
  ): BuildProvider_EntityDisplay {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    try {
      $storage = $etm->getStorage($entityTypeId);
    }
    catch (InvalidPluginDefinitionException $e) {
      throw new EvaluatorException("No entity type storage found for '$entityTypeId'.", 0, $e);
    }

    if (NULL === $entity = $storage->load($entityId)) {
      throw new EvaluatorException("Entity $entityTypeId:$entityId does not exist.");
    }

    return new BuildProvider_EntityDisplay($entity, $entityDisplay);
  }
}
