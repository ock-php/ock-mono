<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_Select_LabelLookupBase;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\cu\TextLookup\TextLookup_Field;
use Drupal\cu\TextLookup\TextLookup_FieldType;
use Psr\Container\ContainerInterface;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_FieldName extends Formula_Select_LabelLookupBase {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private EntityFieldManagerInterface $entityFieldManager;

  /**
   * @var string
   */
  private string $entityTypeId;

  /**
   * @var null|string
   */
  private ?string $bundle;

  /**
   * @var true[]|null
   */
  private ?array $allowedTypesMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $option_label_lookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $group_label_lookup
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param string $entity_type_id
   * @param string|null $bundle
   * @param string[]|null $allowed_types
   */
  public function __construct(
    TextLookupInterface $option_label_lookup,
    TextLookupInterface $group_label_lookup,
    EntityFieldManagerInterface $entity_field_manager,
    string $entity_type_id,
    string $bundle = NULL,
    array $allowed_types = NULL
  ) {
    parent::__construct($option_label_lookup, $group_label_lookup);
    $this->entityFieldManager = $entity_field_manager;
    $this->entityTypeId = $entity_type_id;
    $this->bundle = $bundle;
    $this->allowedTypesMap = array_fill_keys($allowed_types, TRUE);
  }

  /**
   * @param \Psr\Container\ContainerInterface $container
   * @param string $entity_type_id
   * @param string|null $bundle
   * @param string[]|null $allowed_types
   *
   * @return self
   */
  public static function fromContainer(
    ContainerInterface $container,
    string $entity_type_id,
    string $bundle = NULL,
    array $allowed_types = NULL
  ): self {
    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $key_value_factory = $container->get('keyvalue');
    return self::create(
      $container->get('entity_field.manager'),
      $container->get('plugin.manager.field.field_type'),
      $key_value_factory->get('entity.definitions.bundle_field_map'),
      $entity_type_id,
      $bundle,
      $allowed_types);
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundle_field_map
   * @param string $entity_type_id
   * @param string|null $bundle
   * @param string[]|null $allowed_types
   *
   * @return self
   */
  public static function create(
    EntityFieldManagerInterface $entity_field_manager,
    FieldTypePluginManagerInterface $field_type_manager,
    KeyValueStoreInterface $bundle_field_map,
    string $entity_type_id,
    string $bundle = NULL,
    array $allowed_types = NULL
  ): self {
    return new self(
      TextLookup_Field::create(
        $entity_field_manager,
        $bundle_field_map,
        $entity_type_id,
        $bundle),
      new TextLookup_FieldType($field_type_manager),
      $entity_field_manager,
      $entity_type_id,
      $bundle,
      $allowed_types);
  }

  /**
   * @param string $bundle
   *
   * @return static
   */
  public function withBundle(string $bundle): self {
    $clone = clone $this;
    $clone->bundle = $bundle;
    return $clone;
  }

  /**
   * @param string[] $types
   *   Allowed field types.
   *
   * @return static
   */
  public function withAllowedTypes(array $types): self {
    $clone = clone $this;
    $clone->allowedTypesMap = array_fill_keys($types, TRUE);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    $definition = $this->entityFieldManager->getFieldStorageDefinitions($this->entityTypeId)[$id] ?? NULL;
    if ($definition === NULL) {
      return FALSE;
    }
    if ($this->allowedTypesMap !== NULL
      && !isset($this->allowedTypesMap[$definition->getType()])
    ) {
      return FALSE;
    }
    if ($this->bundle !== NULL
      && !$definition instanceof FieldDefinitionInterface
      && !isset($this->entityFieldManager->getFieldDefinitions(
        $this->entityTypeId, $this->bundle)[$id])
    ) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupedIdsMap(): array {
    $definitions = $this->entityFieldManager->getFieldStorageDefinitions($this->entityTypeId);
    if ($this->bundle !== NULL) {
      $definitions = array_intersect_key(
        $definitions,
        $this->entityFieldManager->getFieldDefinitions(
          $this->entityTypeId,# $this->bundle)
        + $this->entityFieldManager->getBaseFieldDefinitions(
          $this->entityTypeId)));
    }
    $grouped_ids_map = [];
    foreach ($definitions as $id => $definition) {
      $grouped_ids_map[$definition->getType()][$id] = TRUE;
    }
    if ($this->allowedTypesMap !== NULL) {
      $grouped_ids_map = array_intersect_key($grouped_ids_map, $this->allowedTypesMap);
    }
    return $grouped_ids_map;
  }

}
