<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntitiesListFormat;

use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence_ItemLabelCallback;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[]
   */
  private $columnDisplays;

  /**
   * @CfrPlugin("simpleTable", "Simple table")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
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
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[] $columnDisplays
   */
  public function __construct(array $columnDisplays) {
    $this->columnDisplays = $columnDisplays;
  }

  /**
   * Displays the entities as a list, e.g. as a table.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array
   */
  public function entitiesBuildList(array $entities) {

    $rows = [];
    foreach ($this->columnDisplays as $colKey => $columnDisplay) {
      foreach ($columnDisplay->buildEntities($entities) as $rowKey => $build) {
        $rows[$rowKey][$colKey]['data'] = $build;
      }
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }
}
