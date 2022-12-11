<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParameters;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\renderkit\TextLookup\TextLookup_FieldName;
use Drupal\renderkit\TextLookup\TextLookup_FieldType;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_FieldName implements Formula_SelectInterface {

  /**
   * @var true[]|null
   */
  private ?array $allowedTypesMap;

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_FieldName $fieldLabelLookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $fieldTypeLabelLookup
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedTypes
   */
  public function __construct(
    private readonly TextLookup_FieldName $fieldLabelLookup,
    private readonly TextLookupInterface $fieldTypeLabelLookup,
    private readonly EntityFieldManagerInterface $entityFieldManager,
    private readonly string $entityTypeId,
    private ?string $bundle = NULL,
    ?array $allowedTypes = NULL,
  ) {
    $this->allowedTypesMap = array_fill_keys($allowedTypes, TRUE);
  }

  /**
   * Proxy formula that does not require services.
   *
   * Adaptism will replace this with the real formula.
   *
   * @param string $entityTypeId
   * @param string[]|null $allowedTypes
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  public static function proxy(
    string $entityTypeId,
    array $allowedTypes = NULL,
  ): FormulaInterface {
    return Formula_FreeParameters::fromFormulaCallback(
      [self::class, 'createReal'],
      [$entityTypeId],
    );
  }

  /**
   * @param string $entityTypeId
   * @param array|null $allowedTypes
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypePluginManager
   * @param \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $keyValueFactory
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createReal(
    string $entityTypeId,
    array $allowedTypes = NULL,
    #[DrupalService('entity_field.manager')]
    EntityFieldManagerInterface $entityFieldManager,
    #[DrupalService('plugin.manager.field.field_type')]
    FieldTypePluginManagerInterface $fieldTypePluginManager,
    #[DrupalService('keyvalue')]
    KeyValueFactoryInterface $keyValueFactory,
  ): FormulaInterface {
    return self::create(
      $entityFieldManager,
      $fieldTypePluginManager,
      $keyValueFactory->get('entity.definitions.bundle_field_map'),
      $entityTypeId,
      NULL,
      $allowedTypes,
    );
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundle_field_map
   * @param string $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedTypes
   *
   * @return self
   */
  public static function create(
    EntityFieldManagerInterface $entity_field_manager,
    FieldTypePluginManagerInterface $field_type_manager,
    KeyValueStoreInterface $bundle_field_map,
    string $entityTypeId,
    string $bundle = NULL,
    array $allowedTypes = NULL,
  ): self {
    return new self(
      TextLookup_EntityField::create(
        $entity_field_manager,
        $bundle_field_map,
        $entityTypeId,
        $bundle,
      ),
      new TextLookup_FieldType($field_type_manager),
      $entity_field_manager,
      $entityTypeId,
      $bundle,
      $allowedTypes,
    );
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
  public function withAllowedTypes(array $types): static {
    $clone = clone $this;
    $clone->allowedTypesMap = array_fill_keys($types, TRUE);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    $definition = $this->entityFieldManager->getFieldStorageDefinitions($this->entityTypeId)[$id]
      ?? NULL;
    if ($definition === NULL) {
      return FALSE;
    }
    if ($this->allowedTypesMap !== NULL
      && !isset($this->allowedTypesMap[$definition->getType()])
    ) {
      return FALSE;
    }
    if ($this->bundle === null) {
      return TRUE;
    }
    if ($definition instanceof FieldDefinitionInterface) {
      // This is a base field that is independent of bundles.
      return TRUE;
    }
    $definitionsInBundle = $this->entityFieldManager->getFieldDefinitions(
      $this->entityTypeId,
      $this->bundle,
    );
    return isset($definitionsInBundle[$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    $definitions = $this->entityFieldManager->getFieldStorageDefinitions($this->entityTypeId);
    if ($this->bundle !== NULL) {
      $definitions = array_intersect_key(
        $definitions,
        $this->entityFieldManager->getFieldDefinitions(
          $this->entityTypeId,
          $this->bundle,
        )
        + $this->entityFieldManager->getBaseFieldDefinitions(
          $this->entityTypeId,
        ),
      );
    }
    $map = [];
    foreach ($definitions as $id => $definition) {
      $type = $definition->getType();
      if ($this->allowedTypesMap !== NULL && !isset($this->allowedTypesMap[$type])) {
        continue;
      }
      $map[$id] = $type;
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(int|string $id): ?TextInterface {
    return $this->fieldLabelLookup->idGetText($id);
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return $this->fieldTypeLabelLookup->idGetText($groupId);
  }

}
