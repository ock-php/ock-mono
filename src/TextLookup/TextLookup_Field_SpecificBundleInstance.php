<?php

declare(strict_types=1);

namespace Drupal\ock\TextLookup;

use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\ock\DrupalText;

class TextLookup_Field_SpecificBundleInstance implements TextLookupInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private EntityFieldManagerInterface $entityFieldManager;

  /**
   * @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface
   */
  private KeyValueStoreInterface $bundleFieldMap;

  private string $entityTypeId;

  private ?string $bundle;

  public static function create(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId,
    string $bundle
  ): self {
    return new self(
      $entityFieldManager,
      $bundleFieldMap,
      $entityTypeId,
      $bundle);
  }

  public static function stub(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId
  ): self {
    return new self(
      $entityFieldManager,
      $bundleFieldMap,
      $entityTypeId);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundleFieldMap
   * @param string $entityTypeId
   * @param string|null $bundle
   */
  private function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId,
    string $bundle = NULL
  ) {
    $this->entityFieldManager = $entityFieldManager;
    $this->entityTypeId = $entityTypeId;
    $this->bundle = $bundle;
  }

  /**
   * @param string $bundle
   *
   * @return $this
   */
  public function withBundle(string $bundle): self {
    $clone = clone $this;
    $clone->bundle = $bundle;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {

    if ($this->bundle === NULL) {
      // This object is a stub.
      return [];
    }

    $definitions = $this->entityFieldManager->getFieldDefinitions(
      $this->entityTypeId,
      $this->bundle);

    // Filter the definitions.
    $definitions = array_intersect_key($definitions, $ids_map);

    $labels = [];
    foreach ($definitions as $field_name => $definition) {
      $labels[$field_name] = DrupalText::fromVar(
        $definition->getLabel());
    }

    return $labels;
  }

}
