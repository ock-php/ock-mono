<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\CallServiceWithArguments;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\ParametricService;
use Donquixote\DID\Attribute\Parameter\GetArgument;
use Donquixote\Ock\Attribute\Parameter\GetContext;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\renderkit\TextLookup\TextLookup_FieldName;
use Drupal\renderkit\TextLookup\TextLookup_FieldType;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
#[ParametricService(self::class)]
class Formula_FieldName implements Formula_SelectInterface {

  /**
   * @var true[]|null
   */
  private ?array $allowedTypesMap;

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_FieldName $fieldLabelLookup
   * @param \Drupal\renderkit\TextLookup\TextLookup_FieldType $fieldTypeLabelLookup
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedTypes
   */
  public function __construct(
    #[CallServiceWithArguments]
    private readonly TextLookup_FieldName $fieldLabelLookup,
    #[GetService]
    private readonly TextLookup_FieldType $fieldTypeLabelLookup,
    #[GetService('entity_field.manager')]
    private readonly EntityFieldManagerInterface $entityFieldManager,
    #[GetArgument]
    private readonly string $entityTypeId,
    #[GetContext]
    private ?string $bundle = NULL,
    #[GetContext]
    ?array $allowedTypes = NULL,
  ) {
    $this->allowedTypesMap = array_fill_keys($allowedTypes, TRUE);
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
