<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\DrupalText;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[Service(self::class)]
class TextLookup_EntityTypeDotId implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityType $entityTypeLabelLookup
   */
  public function __construct(
    #[GetService('entity_type.manager')]
    private readonly EntityTypeManagerInterface $entityTypeManager,
    #[GetService]
    private readonly TextLookup_EntityType $entityTypeLabelLookup,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    [$type, $etid] = explode('.', $id . '.');
    if ('' === $type || '' === $etid) {
      return NULL;
    }
    $entityTypeLabel = $this->entityTypeLabelLookup->idGetText($type);
    if ($entityTypeLabel === NULL) {
      return NULL;
    }
    try {
      $storage = $this->entityTypeManager->getStorage($type);
    }
    catch (PluginException) {
      return NULL;
    }
    $entity = $storage->load($etid);
    if ($entity === NULL) {
      return NULL;
    }
    $entityLabel = DrupalText::fromEntity($entity);
    return Text::label($entityTypeLabel, $entityLabel);
  }

}
