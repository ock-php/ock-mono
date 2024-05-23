<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Multi;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;

class EntityToFieldItemList_Field implements EntityToFieldItemListInterface {

  /**
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $etDotFieldNameFormula
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'field', 'Field')]
  public static function formula(
    #[GetService]
    Formula_EtDotFieldName $etDotFieldNameFormula,
    array $allowedFieldTypes = NULL,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'field',
        Text::t('Field'),
        $etDotFieldNameFormula->withAllowedFieldTypes($allowedFieldTypes),
      )
      ->addStringParts(['entity_type', 'field_name'], '.', 'field',)
      ->construct(self::class, ['entity_type', 'field_name',]);
  }

  /**
   * Constructor.
   *
   * @param string $entityTypeId
   * @param string $fieldName
   */
  public function __construct(
    private readonly string $entityTypeId,
    private readonly string $fieldName
  ) {}

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   */
  public function entityGetItemList(FieldableEntityInterface $entity): ?FieldItemListInterface {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return NULL;
    }

    return $entity->get($this->fieldName);
  }
}
