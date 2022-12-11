<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */

class TextLookup_EntityId implements TextLookupInterface {

  const MAP_SERVICE_ID = 'renderkit.map.text_lookup.entity_id';

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entityStorage
   */
  public function __construct(
    private readonly EntityStorageInterface $entityStorage,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityType
   *
   * @return static
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function create(
    EntityTypeManagerInterface $entityTypeManager,
    string $entityType,
  ): self {
    $storage = $entityTypeManager->getStorage($entityType);
    return new self($storage);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @return callable(string): self
   */
  #[RegisterService(self::MAP_SERVICE_ID)]
  public static function createMap(
    #[GetService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
  ): \Closure {
    return fn (string $entityType) => self::create(
      $entityTypeManager,
      $entityType,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    $entity = $this->entityStorage->load($id);
    if ($entity === NULL) {
      return NULL;
    }
    return DrupalText::fromEntity($entity);
  }

}
