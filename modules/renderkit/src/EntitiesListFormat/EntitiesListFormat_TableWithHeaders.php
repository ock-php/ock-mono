<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Ock\Ock\Formula\Textfield\Formula_Textfield_NoValidation;
use Ock\Ock\Text\Text;

#[OckPluginInstance('table', 'Table with headers')]
class EntitiesListFormat_TableWithHeaders implements EntitiesListFormatInterface {

  /**
   * Constructor.
   *
   * @param array<array-key, array{header: string, display: EntityDisplayInterface}> $columns
   */
  public function __construct(
    #[OckOption('columns', 'Columns')]
    #[OckFormulaFromCall([self::class, 'createColumnsFormula'])]
    private readonly array $columns,
  ) {}

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
   *
   * @internal
   */
  public static function createColumnsFormula(): FormulaInterface {
    $columnFormula = Formula::group()
      ->add(
        'header',
        Text::t('Header'),
        new Formula_Textfield_NoValidation(),
      )
      ->add(
        'display',
        Text::t('Display'),
        Formula::iface(EntityDisplayInterface::class),
      )
      ->buildGroupFormula();
    return new Formula_Sequence_ItemLabelT(
      $columnFormula,
      Text::t('New column'),
      Text::t('Column #!n'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function entitiesBuildList(array $entities): array {
    $header = [];
    $rows = [];
    foreach ($this->columns as $colKey => $column) {
      $header[$colKey] = $column['header'];
      foreach ($column['display']->buildEntities($entities) as $rowKey => $build) {
        $rows[$rowKey][$colKey]['data'] = $build;
      }
    }
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];
  }

}
