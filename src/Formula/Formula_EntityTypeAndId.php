<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\Text\Text;
use Drupal\ock\Attribute\DI\DrupalServiceFromType;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\renderkit\Util\UtilBase;

final class Formula_EntityTypeAndId extends UtilBase {

  const SERVICE_ID = 'renderkit.formula.entity_type_and_id';

  /**
   * @param \Drupal\renderkit\Formula\Formula_EntityType $entityTypeFormula
   * @param callable(string): \Drupal\renderkit\Formula\Formula_EntityIdAutocomplete $entityIdFormulaMap
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[RegisterService(self::SERVICE_ID)]
  public static function create(
    #[DrupalServiceFromType]
    Formula_EntityType $entityTypeFormula,
    #[DrupalService(Formula_EntityIdAutocomplete::MAP_SERVICE_ID)]
    callable $entityIdFormulaMap,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'entity_type',
        Text::t('Entity type'),
        Formula_EntityType::proxy(),
      )
      ->addDynamicFormula(
        'entity_id',
        Text::t('Entity'),
        ['entity_type'],
        $entityIdFormulaMap,
      )
      ->buildGroupFormula();
  }
}
