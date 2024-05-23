<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;
use Ock\DID\Util\PhpUtil;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\ock\Util\DrupalPhpUtil;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;

class EntityToEntity_EntityReferenceField implements EntityToEntityInterface {

  /**
   * Construct.
   *
   * @param string $entityTypeId
   * @param string $fieldName
   * @param string $targetType
   */
  public function __construct(
    private readonly string $entityTypeId,
    private readonly string $fieldName,
    private readonly string $targetType,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $fieldNameFormula
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(self::class, 'entityReferenceField', 'Entity reference field')]
  public static function createFormula(
    #[GetService('entity_field.manager')]
    EntityFieldManagerInterface $entityFieldManager,
    #[GetService]
    Formula_EtDotFieldName $fieldNameFormula,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'reference_field',
        Text::t('Reference field'),
        // @todo Only allow reference fields!
        $fieldNameFormula->withAllowedFieldTypes(['image']),
      )
      ->callPhp([self::class, 'create'], [
        DrupalPhpUtil::service('entity_field.manager'),
        PhpUtil::phpPlaceholder('reference_field'),
      ]);
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $etDotFieldName
   *
   * @return self|null
   */
  public static function create(
    EntityFieldManagerInterface $entityFieldManager,
    string $etDotFieldName,
  ): ?self {
    [$entityTypeId, $fieldName] = explode('.', $etDotFieldName) + [NULL, NULL];
    if (NULL === $fieldName || '' === $entityTypeId || '' === $fieldName) {
      // @todo Throw an exception instead.
      return NULL;
    }

    $targetTypeId = ($entityFieldManager->getFieldStorageDefinitions(
      $entityTypeId,
    )[$fieldName] ?? NULL)?->getSetting('target_type');
    if ($targetTypeId === NULL) {
      return NULL;
    }

    return new self($entityTypeId, $fieldName, $targetTypeId);
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType(): string {
    return $this->targetType;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  public function entityGetRelated(EntityInterface $entity): ?EntityInterface {

    if (!$entity instanceof FieldableEntityInterface) {
      return NULL;
    }

    if ($entity->getEntityTypeId() !== $this->entityTypeId) {
      return NULL;
    }

    try {
      $item = $entity->get($this->fieldName)->first();
    }
    catch (MissingDataException $e) {
      // No need to log this, it just means the field is empty.
      unset($e);
      return NULL;
    }

    if (!$item instanceof EntityReferenceItem) {
      return NULL;
    }

    $referencedEntity = $item->__get('entity');

    if (!$referencedEntity instanceof EntityInterface) {
      return NULL;
    }

    if ($this->targetType !== $referencedEntity->getEntityTypeId()) {
      return NULL;
    }

    return $referencedEntity;
  }
}
