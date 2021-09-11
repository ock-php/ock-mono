<?php

declare(strict_types=1);

namespace Drupal\ock\TextLookup;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;

class TextLookup_Field_CombineBundleInstances implements TextLookupInterface {

  /**
   * @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface
   */
  private KeyValueStoreInterface $bundleFieldMap;

  /**
   * @var string
   */
  private string $entityTypeId;

  /**
   * @var \Drupal\ock\TextLookup\TextLookup_Field_SpecificBundleInstance
   */
  private TextLookup_Field_SpecificBundleInstance $stub;

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundleFieldMap
   * @param string $entityTypeId
   * @param string|null $bundle
   *
   * @return \Donquixote\Ock\TextLookup\TextLookupInterface
   */
  public static function create(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId,
    string $bundle = NULL
  ): TextLookupInterface {
    return ($bundle === NULL)
      ? new self(
        $entityFieldManager,
        $bundleFieldMap,
        $entityTypeId)
      : TextLookup_Field_SpecificBundleInstance::create(
        $entityFieldManager,
        $bundleFieldMap,
        $entityTypeId,
        $bundle);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundleFieldMap
   * @param string $entityTypeId
   */
  public function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId
  ) {
    $this->bundleFieldMap = $bundleFieldMap;
    $this->entityTypeId = $entityTypeId;
    $this->stub = TextLookup_Field_SpecificBundleInstance::stub(
      $entityFieldManager,
      $bundleFieldMap,
      $entityTypeId);
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {

    /**
     * @var string[][]|\Drupal\Core\StringTranslation\TranslatableMarkup[][] $labelss
     *   Format: $[$field_name][$bundle] = $label
     */
    $labelss = [];
    foreach ($this->getFieldNamesByBundle($ids_map) as $bundle => $bundle_field_names) {
      $lookup = $this->stub->withBundle($bundle);
      $bundle_field_labels = $lookup->idsMapGetTexts($bundle_field_names);
      foreach ($bundle_field_labels as $field_name => $field_label) {
        $labelss[$field_name][$bundle] = $field_label;
      }
    }

    $labels = [];
    foreach ($labelss as $field_name => $field_labels) {
      $labels[$field_name] = Text::concatDistinct($field_labels);
    }

    return $labels;
  }

  /**
   * @param mixed[] $field_names_map
   *
   * @return string[][]
   */
  private function getFieldNamesByBundle(array $field_names_map): array {

    /**
     * @var string[][][] $bundle_field_map
     *   Format: $[$fieldName]['bundles'][] = $bundle
     */
    $bundle_field_map = $this->bundleFieldMap->get($this->entityTypeId, []);
    $bundle_field_map = array_intersect_key($bundle_field_map, $field_names_map);

    /**
     * @var string[][] $field_names_by_bundle
     *   Format: $[$bundle][$field_name] = $field_name
     */
    $field_names_by_bundle = [];
    foreach ($bundle_field_map as $field_name => $map_entry) {
      foreach ($map_entry['bundles'] as $bundle) {
        $field_names_by_bundle[$bundle][$field_name] = $field_name;
      }
    }

    return $field_names_by_bundle;
  }

}
