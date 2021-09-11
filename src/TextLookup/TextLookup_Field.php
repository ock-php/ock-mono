<?php

declare(strict_types=1);

namespace Drupal\cu\TextLookup;

use Donquixote\ObCK\TextLookup\TextLookup_FallbackChain;
use Donquixote\ObCK\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Psr\Container\ContainerInterface;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
class TextLookup_Field {

  /**
   * @param \Psr\Container\ContainerInterface $container
   * @param string $entity_type_id
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\TextLookup\TextLookupInterface
   */
  public static function fromContainer(ContainerInterface $container, string $entity_type_id, string $bundle = NULL): TextLookupInterface {
    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $key_value_factory = $container->get('keyvalue');
    return self::create(
      $container->get('entity_field.manager'),
      $key_value_factory->get('entity.definitions.bundle_field_map'),
      $entity_type_id,
      $bundle);
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundleFieldMap
   * @param string $entityTypeId
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\TextLookup\TextLookupInterface
   */
  public static function create(
    EntityFieldManagerInterface $entityFieldManager,
    KeyValueStoreInterface $bundleFieldMap,
    string $entityTypeId,
    string $bundle = NULL
  ): TextLookupInterface {
    return new TextLookup_FallbackChain(
      [
        new TextLookup_Field_BaseField(
          $entityFieldManager,
          $entityTypeId),
        TextLookup_Field_CombineBundleInstances::create(
          $entityFieldManager,
          $bundleFieldMap,
          $entityTypeId,
          $bundle)
      ]);
  }

}
