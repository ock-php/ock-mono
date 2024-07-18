<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\DID\Attribute\Service;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Group\Formula_Group;
use Ock\Ock\Text\Text;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Service]
class Formula_EntityTypeAndId extends Formula_Group {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\Formula\Formula_EntityType $entityTypeFormula
   * @param callable(string): \Drupal\renderkit\Formula\Formula_EntityIdAutocomplete $entityIdFormulaMap
   *
   * @throws \Ock\Ock\Exception\FormulaException
   * @throws \Ock\Ock\Exception\GroupFormulaDuplicateKeyException
   */
  public function __construct(
    Formula_EntityType $entityTypeFormula,
    #[Autowire(Formula_EntityIdAutocomplete::LOOKUP_SERVICE_ID)]
    callable $entityIdFormulaMap,
  ) {
    $items = Formula::group()
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
      ->buildGroupFormula()
      ->getItems();
    parent::__construct($items);
  }

}
