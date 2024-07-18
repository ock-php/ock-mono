<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\renderkit\Util\UtilBase;
use Ock\DID\Attribute\Service;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class Formula_EntityTypeAndId extends UtilBase {

  /**
   * @param \Drupal\renderkit\Formula\Formula_EntityType $entityTypeFormula
   * @param callable(string): \Drupal\renderkit\Formula\Formula_EntityIdAutocomplete $entityIdFormulaMap
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  #[Service(serviceIdSuffix: self::class)]
  public static function create(
    Formula_EntityType $entityTypeFormula,
    #[Autowire(Formula_EntityIdAutocomplete::LOOKUP_SERVICE_ID)]
    callable $entityIdFormulaMap,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'entity_type',
        Text::t('Entity type'),
        $entityTypeFormula,
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
