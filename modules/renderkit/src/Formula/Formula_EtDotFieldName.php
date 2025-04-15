<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\renderkit\TextLookup\TextLookup_EntityFieldWithEntityType;
use Drupal\renderkit\TextLookup\TextLookup_FieldType;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Text\TextInterface;

/**
 * Select formula where the value is like 'node.body'.
 */
#[Service]
class Formula_EtDotFieldName implements Formula_SelectInterface {

  /**
   * @var null|string[]
   */
  private ?array $allowedFieldTypes = NULL;

  /**
   * @var null|string
   */
  private ?string $entityType = NULL;

  /**
   * @var null|string
   */
  private ?string $bundleName = NULL;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\renderkit\TextLookup\TextLookup_FieldType $fieldTypeLabelLookup
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityFieldWithEntityType $fieldLabelLookup
   */
  public function __construct(
    private readonly EntityFieldManagerInterface $entityFieldManager,
    private readonly TextLookup_FieldType $fieldTypeLabelLookup,
    private TextLookup_EntityFieldWithEntityType $fieldLabelLookup,
  ) {}

  /**
   * @param string[]|null $allowedTypes
   *
   * @return $this
   */
  public function withAllowedFieldTypes(?array $allowedTypes): static {
    $clone = clone $this;
    $clone->allowedFieldTypes = $allowedTypes;
    return $clone;
  }

  /**
   * @param string|null $entity_type
   * @param string|null $bundle
   *
   * @return static
   */
  public function withEntityType(?string $entity_type, string $bundle = NULL): static {
    if ($entity_type === NULL && $bundle !== NULL) {
      throw new \InvalidArgumentException('Bundle must be NULL if entity type is NULL.');
    }
    $clone = clone $this;
    $clone->entityType = $entity_type;
    $clone->bundleName = $bundle;
    // When a bundle is specified, the field label lookup should only take the
    // label from that bundle into account.
    // @todo When no bundle is specified, the constraint needs to be removed
    //   from the field label lookup. Currently this is not possible.
    if ($bundle !== NULL) {
      $clone->fieldLabelLookup = $clone->fieldLabelLookup->withEntityBundles(
        $entity_type,
        [$bundle => TRUE],
      );
    }
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $fmap = $this->entityFieldManager->getFieldMap();
    $map = [];
    foreach ($fmap as $entityType => $fields) {
      if ($this->entityType !== NULL && $this->entityType !== $entityType) {
        continue;
      }
      foreach ($fields as $fieldName => $fieldInfo) {
        if ($this->bundleName !== NULL && !isset($fieldInfo['bundles'][$this->bundleName])) {
          continue;
        }
        $map[$entityType . '.' . $fieldName] = $fieldInfo['type'];
      }
    }
    if ($this->allowedFieldTypes !== NULL) {
      $map = array_intersect($map, $this->allowedFieldTypes);
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return $this->fieldTypeLabelLookup->idGetText($groupId);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    return $this->fieldLabelLookup->idGetText($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    [$entityType, $fieldName] = explode('.', $id . '.');

    if ($this->entityType !== NULL && $this->entityType !== $entityType) {
      return FALSE;
    }

    /**
     * @var array<string, array<string, array{type: string, bundles: array<string, string>}>> $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    if (!isset($map[$entityType][$fieldName])) {
      return FALSE;
    }

    $fieldInfo = $map[$entityType][$fieldName];

    if ($this->bundleName !== NULL && !isset($fieldInfo['bundles'][$this->bundleName])) {
      return FALSE;
    }

    if (NULL !== $this->allowedFieldTypes) {
      if (!\in_array($fieldInfo['type'], $this->allowedFieldTypes, TRUE)) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
