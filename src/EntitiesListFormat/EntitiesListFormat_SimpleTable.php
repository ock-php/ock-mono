<?php

namespace Drupal\renderkit\EntitiesListFormat;

use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_ItemLabelCallback;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  private $columnDisplays;

  /**
   * @CfrPlugin("simpleTable", "Simple table")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_ValueToValue_CallbackMono::fromClass(
      __CLASS__,
      new CfSchema_Sequence_ItemLabelCallback(
        new CfSchema_IfaceWithContext(EntityDisplayInterface::class),
        function($delta) {
          return NULL === $delta
            ? t('New column')
            : t('Column #@n', ['@n' => $delta + 1]);
        }));
  }

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $columnDisplays
   */
  public function __construct(array $columnDisplays) {
    $this->columnDisplays = $columnDisplays;
  }

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array
   *   A render array.
   */
  public function entitiesBuildList($entityType, array $entities) {

    $rows = [];
    foreach ($this->columnDisplays as $colKey => $columnDisplay) {
      foreach ($columnDisplay->buildEntities($entityType, $entities) as $rowKey => $build) {
        $rows[$rowKey][$colKey] = drupal_render($build);
      }
    }

    return [
      /* @see theme_table() */
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }
}
