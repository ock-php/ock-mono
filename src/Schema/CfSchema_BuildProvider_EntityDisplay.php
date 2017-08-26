<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\IdToSchema\IdToSchema_Class;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\V2V\Group\V2V_GroupInterface;
use Drupal\renderkit8\BuildProvider\BuildProvider_EntityDisplay;
use Drupal\renderkit8\EntityDisplay\EntityDisplay;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class CfSchema_BuildProvider_EntityDisplay implements CfSchema_GroupInterface, V2V_GroupInterface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function createDrilldown() {
    return CfSchema_Drilldown::create(
      CfSchema_EntityType_WithGroupLabels::create(),
      new IdToSchema_Class(self::class));
  }

  /**
   * @param string $entityTypeId
   */
  public function __construct($entityTypeId) {
    $this->entityTypeId = $entityTypeId;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas() {
    return [
      'entity_id' => new CfSchema_EntityId($this->entityTypeId),
      'entity_display' => EntityDisplay::schema($this->entityTypeId),
    ];
  }

  /**
   * @return string[]
   */
  public function getLabels() {
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
   * @throws \Donquixote\Cf\Exception\EvaluatorException
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
  public function itemsPhpGetPhp(array $itemsPhp) {
    return '\\' . self::class . '::createBuildProvider('
      . "\n" . var_export($this->entityTypeId, TRUE)
      . "\n" . $itemsPhp['entity_id']
      . "\n" . $itemsPhp['entity_display'] . ')';

  }

  /**
   * @param string $entityTypeId
   * @param int|string $entityId
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   *
   * @return \Drupal\renderkit8\BuildProvider\BuildProvider_EntityDisplay
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public static function createBuildProvider(
    $entityTypeId,
    $entityId,
    EntityDisplayInterface $entityDisplay
  ) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    $storage = $etm->getStorage($entityTypeId);

    if (NULL === $entity = $storage->load($entityId)) {
      throw new EvaluatorException("Entity $entityTypeId:$entityId does not exist.");
    }

    return new BuildProvider_EntityDisplay($entity, $entityDisplay);
  }
}
