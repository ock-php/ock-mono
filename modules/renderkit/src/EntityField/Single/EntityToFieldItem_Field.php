<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Single;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;

class EntityToFieldItem_Field implements EntityToFieldItemInterface {

  /**
   * @param string $entityTypeId
   * @param string $fieldName
   */
  public function __construct(
    private readonly string $entityTypeId,
    private readonly string $fieldName,
  ) {}

  /**
   * @CfrPlugin("field", "Field", inline = true)
   *
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function formula(
    #[GetService]
    Formula_EtDotFieldName $fieldNameFormula,
    // @todo Inject these from context.
    array $allowedFieldTypes = NULL,
    string $entityType = NULL,
    string $bundle = NULL,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'field',
        Text::t('Field'),
        $fieldNameFormula,
      )
      ->addStringParts(['entity_type', 'field_name'], '.', 'field')
      ->construct(self::class, ['entity_type', 'field_name']);
  }

  /**
   * @param array $settings
   *
   * @return self
   */
  public static function create(array $settings): self {
    return new self(
      $settings['entity_type'],
      $settings['field_name'],
    );
  }

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemInterface|null
   */
  public function entityGetItem(FieldableEntityInterface $entity): ?FieldItemInterface {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return NULL;
    }

    try {
      $item = $entity->get($this->fieldName)->first();
    }
    catch (MissingDataException $e) {
      unset($e);
      return null;
    }

    if (!$item instanceof FieldItemInterface) {
      return NULL;
    }

    return $item;
  }
}
