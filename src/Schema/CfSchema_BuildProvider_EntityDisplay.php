<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Context\CfContext;
use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;
use Drupal\renderkit8\BuildProvider\BuildProvider_EntityDisplay;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class CfSchema_BuildProvider_EntityDisplay extends CfSchema_Drilldown_OptionsSchemaBase implements V2V_DrilldownInterface {

  public function __construct() {
    parent::__construct(CfSchema_EntityType_WithGroupLabels::create());
  }

  /**
   * @param string|int $entityTypeId
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId) {

    return new CfSchema_Group(
      [
        'entity_id' => new CfSchema_EntityId($entityTypeId),
        'entity_display' => CfSchema::iface(
          EntityDisplayInterface::class,
          new CfContext(
            [
              'entityType' => $entityTypeId,
              'entity_type' => $entityTypeId,
              'entityTypeId' => $entityTypeId,
            ])),
      ],
      [
        'entity_id' => t('Entity id'),
        'entity_display' => t('Entity display'),
      ]);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    return 'entity_type';
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return 'etid_and_display';
  }

  /**
   * @param string|int $entityTypeId
   * @param mixed $etidAndDisplay
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($entityTypeId, $etidAndDisplay) {
    return self::createBuildProvider(
      $entityTypeId,
      $etidAndDisplay);
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return string
   */
  public function idPhpGetPhp($id, $php) {
    return '\\' . self::class . '::createBuildProvider('
      . "\n" . var_export($id, TRUE) . ','
      . "\n" . $php . ')';
  }

  /**
   * @param string $entityTypeId
   * @param array $etidAndDisplay
   *
   * @return \Drupal\renderkit8\BuildProvider\BuildProvider_EntityDisplay
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public static function createBuildProvider($entityTypeId, array $etidAndDisplay) {

    $etid = $etidAndDisplay['entity_id'];
    $display = $etidAndDisplay['entity_display'];

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    $etm = \Drupal::service('entity_type.manager');

    $storage = $etm->getStorage($entityTypeId);

    if (NULL === $entity = $storage->load($etid)) {
      throw new EvaluatorException("Entity does not exist.");
    }

    if (!$display instanceof EntityDisplayInterface) {
      throw new EvaluatorException("Expected an EntityDisplayInterface object.");
    }

    return new BuildProvider_EntityDisplay($entity, $display);
  }
}
