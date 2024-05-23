<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\Text\Text;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;

#[Service(self::class)]
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
    #[GetService('entity_field.manager')]
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
   * @param string $entityType
   * @param string $fieldName
   *
   * @return \Ock\Ock\Text\TextInterface|null
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
   * @param string $entityType
   * @param string $fieldName
   *
   * @return \Ock\Ock\Text\TextInterface|null
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
