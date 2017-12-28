<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityToEntity;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\renderkit8\Schema\CfSchema_EtDotFieldName_EntityReference;

class EntityToEntity_EntityReferenceField implements EntityToEntityInterface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $targetType;

  /**
   * @CfrPlugin("entityReferenceField", "Entity reference field")
   *
   * @param string $entityType
   *   (optional) Contextual parameter.
   * @param string $bundleName
   *   (optional) Contextual parameter.
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL, $bundleName = NULL): CfSchemaInterface {

    $etDotFieldNameSchema = new CfSchema_EtDotFieldName_EntityReference(
      $entityType,
      $bundleName,
      NULL);

    return CfSchema_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'create',
      $etDotFieldNameSchema);
  }

  /**
   * @param string $etDotFieldName
   *
   * @return self|null
   */
  public static function create($etDotFieldName): ?self {

    list($entityTypeId, $fieldName) = explode('.', $etDotFieldName) + [NULL, NULL];

    if (NULL === $fieldName || '' === $entityTypeId || '' === $fieldName) {
      return NULL;
    }

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    $storages = $efm->getFieldStorageDefinitions($entityTypeId);

    if (!isset($storages[$fieldName])) {
      return NULL;
    }

    $storage = $storages[$fieldName];

    if (NULL === $targetTypeId = $storage->getSetting('target_type')) {
      return NULL;
    }

    return new self($entityTypeId, $fieldName, $targetTypeId);
  }

  /**
   * @param string $entityTypeId
   * @param string $fieldName
   * @param string $targetType
   */
  public function __construct($entityTypeId, $fieldName, $targetType) {
    $this->entityTypeId = $entityTypeId;
    $this->fieldName = $fieldName;
    $this->targetType = $targetType;
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

    $targetEntity = $item->entity;

    if (!$targetEntity instanceof EntityInterface) {
      return NULL;
    }

    if ($this->targetType !== $targetEntity->getEntityTypeId()) {
      return NULL;
    }

    return $targetEntity;
  }
}
