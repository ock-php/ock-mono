<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\ObCK\Formula\Sequence\Formula_Sequence_ItemLabelCallback;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  private $columnDisplays;

  /**
   * @CfrPlugin("simpleTable", "Simple table")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createFormula() {

    return Formula_ValueToValue_CallbackMono::fromClass(
      __CLASS__,
      new Formula_Sequence_ItemLabelCallback(
        new Formula_IfaceWithContext(EntityDisplayInterface::class),
        static function ($delta) {
          return NULL === $delta
            ? '' . t('New column')
            : '' . t('Column #@n', ['@n' => $delta + 1]);
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
