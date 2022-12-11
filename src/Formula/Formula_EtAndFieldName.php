<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\renderkit\Util\UtilBase;

/**
 * Formula where the value is like ['entity_type' => 'node', 'field_name' => 'body'].
 */
final class Formula_EtAndFieldName extends UtilBase {

  const SERVICE_ID = 'renderkit.formula.entity_type_and_field_name';

  /**
   * @param list<string>|null $allowedTypes
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  public static function create(
    array $allowedTypes = NULL,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'entity_type',
        Text::t('Entity type'),
        Formula_EntityType::proxy(),
      )
      ->addDynamicFormula(
        'field_name',
        Text::t('Field name'),
        ['entity_type'],
        fn (string $entityTypeId) => Formula_FieldName::proxy(
          $entityTypeId,
          $allowedTypes,
        )
      )
      ->buildGroupFormula();
  }
}
