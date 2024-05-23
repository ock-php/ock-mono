<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[Service(self::class)]
class TextLookup_EntityFieldWithEntityType extends TextLookup_CombinedLabelBase {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityType $entityTypeLabelLookup
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityField $entityFieldLabelLookup
   */
  public function __construct(
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
    #[GetService]
    private readonly TextLookup_EntityField $entityFieldLabelLookup,
  ) {
    parent::__construct(
      $entityTypeLabelLookup,
      $entityFieldLabelLookup,
    );
  }

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
    return $this->withDecoratedLabelLookup(
      $this->entityFieldLabelLookup->withEntityBundles($entityType, $bundles),
    );
  }

}
