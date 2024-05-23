<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;

/**
 * Formula to choose entity reference fields.
 */
#[Service(self::class)]
class Formula_EtDotFieldName_EntityReference implements Formula_SelectInterface {

  private ?string $targetEntityTypeId;

  private readonly Formula_EtDotFieldName $decorated;

  /**
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $decorated
   */
  public function __construct(
    #[GetService]
    Formula_EtDotFieldName $decorated,
    #[GetService('entity_field.manager')]
    private readonly EntityFieldManagerInterface $entityFieldManager,
    #[GetService('plugin.manager.field.field_type')]
    FieldTypePluginManagerInterface $fieldTypeManager,
    #[GetService]
    private readonly TextLookup_EntityType $entityTypeLabelLookup,
  ) {
    $this->decorated = $decorated->withAllowedFieldTypes(
      $this->getAllowedFieldTypes($fieldTypeManager),
    );
  }

  private function getAllowedFieldTypes(FieldTypePluginManagerInterface $fieldTypeManager): array {
    $types = [];
    foreach ($fieldTypeManager->getDefinitions() as $type => $definition) {
      $class = $definition['class'] ?? NULL;
      if (!$class
        || !class_exists($class)
        || !is_a($class, EntityReferenceItem::class)
      ) {
        continue;
      }
      $types[] = $type;
    }
    return $types;
  }

  /**
   * @param string $targetTypeId
   *
   * @return static
   */
  public function withTargetType(string $targetTypeId): static {
    $clone = clone $this;
    $clone->targetEntityTypeId = $targetTypeId;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(int|string $id): bool {
    if (!$this->decorated->idIsKnown($id)) {
      return FALSE;
    }
    [$entityType, $fieldName] = explode('.', $id);
    $storage = $this->entityFieldManager->getFieldStorageDefinitions($entityType)[$fieldName] ?? NULL;
    if ($storage === NULL) {
      return FALSE;
    }
    if ($this->targetEntityTypeId !== NULL) {
      if ($storage->getSetting('target_type') !== $this->targetEntityTypeId) {
        return FALSE;
      }
    }
    else {
      if ($storage->getSetting('target_type') === NULL) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(int|string $id): ?TextInterface {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    $map = [];
    $storagess = [];
    foreach ($this->decorated->getOptionsMap() as $id => $fieldTypeId) {
      [$entityType, $fieldName] = explode('.', $id);
      $storage = ($storagess[$entityType] ??= $this->entityFieldManager
        ->getFieldStorageDefinitions($entityType))
        [$fieldName] ?? NULL;
      if ($storage === NULL) {
        continue;
      }
      $targetEntityTypeId = $storage->getSetting('target_type');
      if ($this->targetEntityTypeId !== NULL) {
        // Group by field types.
        if ($targetEntityTypeId !== $this->targetEntityTypeId) {
          continue;
        }
        $map[$id] = $fieldTypeId;
      }
      else {
        // Group by target entity types instead of field types.
        if ($targetEntityTypeId === NULL) {
          continue;
        }
        $map[$id] = $targetEntityTypeId;
      }
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    if ($this->targetEntityTypeId !== NULL) {
      return $this->decorated->groupIdGetLabel($groupId);
    }
    return $this->entityTypeLabelLookup->idGetText($groupId);
  }

}
