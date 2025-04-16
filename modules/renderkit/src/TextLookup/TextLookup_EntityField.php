<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Text\Text;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

/**
 * Text lookup to get a field label.
 *
 * The id parameter is like "$entity_type.$field_machine_name".
 * The return value is a field label.
 *
 * This works both for base fields and for bundle fields.
 * For bundle fields, it will concatenate distinct labels from multiple bundles.
 */
#[Service]
class TextLookup_EntityField implements TextLookupInterface {

  /**
   * Map of bundles that should be considered for field labels.
   *
   * For entity types that are not in this map, all bundles will be used.
   *
   * @var array<string, array<string, mixed>>
   *   Format: $[$entityType][$bundle] = $_.
   */
  private array $entityBundles = [];

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  public function __construct(
    private readonly EntityFieldManagerInterface $entityFieldManager,
  ) {}

  /**
   * Immutable setter. Restricts the bundles to be considered for field labels.
   *
   * @param string $entityType
   * @param array<string, mixed> $bundles
   *   Format: $[$bundle] = $_.
   *
   * @return static
   */
  public function withEntityBundles(string $entityType, array $bundles): static {
    $clone = clone $this;
    $clone->entityBundles[$entityType] = $bundles;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    [$entityType, $fieldName] = explode('.', $id);
    return $this->findBaseFieldLabel($entityType, $fieldName)
      ?? $this->findCombinedBundleFieldLabel($entityType, $fieldName);
  }

  /**
   * Finds a field label for an entity base field.
   *
   * @param string $entityType
   * @param string $fieldName
   *
   * @return \Ock\Ock\Text\TextInterface|null
   *   The base field label, or NULL if the field is not found or has no label.
   */
  private function findBaseFieldLabel(string $entityType, string $fieldName): ?TextInterface {
    $definition = $this->entityFieldManager->getBaseFieldDefinitions(
      $entityType,
    )[$fieldName] ?? NULL;
    if ($definition !== NULL) {
      return DrupalText::fromVar($definition->getLabel());
    }
    return NULL;
  }

  /**
   * Finds a field label for a bundle field.
   *
   * @param string $entityType
   * @param string $fieldName
   *
   * @return \Ock\Ock\Text\TextInterface|null
   *   The label, or NULL if field not found or has no label.
   */
  private function findCombinedBundleFieldLabel(string $entityType, string $fieldName): ?TextInterface {
    /**
     * @var array<string, string> $bundles
     *   Bundles where this field exists.
     *   Format: $[$bundle] = $bundle.
     */
    $bundles = $this->entityFieldManager->getFieldMap()[$entityType][$fieldName]['bundles'];
    if (isset($this->entityBundles[$entityType])) {
      $bundles = array_intersect_key($bundles, $this->entityBundles[$entityType]);
    }
    if (!$bundles) {
      return NULL;
    }
    $labels = [];
    foreach ($bundles as $bundle) {
      $label = ($this->entityFieldManager->getFieldDefinitions(
        $entityType,
        $bundle,
      )[$fieldName] ?? NULL)?->getLabel();
      if ($label === NULL) {
        continue;
      }
      $labels[] = DrupalText::fromVar($label);
    }
    if (!$labels) {
      return NULL;
    }
    if (count($labels) === 1) {
      return reset($labels);
    }
    return Text::concatDistinct($labels);
  }

}
