<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\GetCallableService;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\renderkit\Util\UtilBase;

final class Formula_EntityTypeAndId extends UtilBase {

  /**
   * @param \Drupal\renderkit\Formula\Formula_EntityType $entityTypeFormula
   * @param callable(string): \Drupal\renderkit\Formula\Formula_EntityIdAutocomplete $entityIdFormulaMap
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[Service(serviceIdSuffix: self::class)]
  public static function create(
    #[GetService]
    Formula_EntityType $entityTypeFormula,
    #[GetCallableService(Formula_EntityIdAutocomplete::class)]
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