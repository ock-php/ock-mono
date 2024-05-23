<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\DID\Attribute\Parameter\CallServiceWithArguments;
use Donquixote\DID\Attribute\ParametricService;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\DrupalText;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[ParametricService(self::class)]
class TextLookup_EntityId implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entityStorage
   */
  public function __construct(
    #[CallServiceWithArguments]
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
