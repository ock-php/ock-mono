<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;

#[OckPluginInstance('tableSimple', 'Table without headers')]
class EntitiesListFormat_SimpleTable implements EntitiesListFormatInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[] $columnDisplays
   */
  public function __construct(
    #[OckOption('columns', 'Columns')]
    #[OckFormulaFromCall([self::class, 'createColumnsFormula'])]
    private readonly array $columnDisplays,
  ) {}

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  public static function createColumnsFormula(): FormulaInterface {
    return Formula::ifaceSequence(EntityDisplayInterface::class)
      ->withItemLabels(
        Text::t('New column'),
        Text::t('Column #!n'),
      );
  }

  /**
   * {@inheritdoc}
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
