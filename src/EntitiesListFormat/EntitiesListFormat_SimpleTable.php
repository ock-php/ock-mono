<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence_ItemLabelCallback;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   */
  private $columnDisplays;

  /**
   * @CfrPlugin("simpleTable", "Simple table")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {

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
  public function entitiesBuildList(array $entities): array {

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
