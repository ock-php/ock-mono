<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Ock\DependencyInjection\Attribute\Service;
use Drupal\ock\DrupalText;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[Service]
class TextLookup_EntityType implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(
    #[GetService('entity_type.manager')]
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {}

  public function idGetText(int|string $id): ?TextInterface {
    try {
      $definition = $this->entityTypeManager->getDefinition($id, FALSE);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Unexpected exception.', 0, $e);
    }
    if ($definition === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($definition->getLabel() ?? $id);
  }

}
