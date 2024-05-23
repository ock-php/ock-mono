<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntitiesListFormat;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromCall;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Donquixote\Ock\Formula\Textfield\Formula_Textfield_NoValidation;
use Donquixote\Ock\Text\Text;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
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
