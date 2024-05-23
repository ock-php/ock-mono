<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\ParametricService;
use Ock\DID\Attribute\Parameter\GetArgument;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[ParametricService]
class TextLookup_FieldName implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityField $entityFieldLabelLookup
   * @param string $entityType
   */
  public function __construct(
    #[GetService]
    private TextLookup_EntityField $entityFieldLabelLookup,
    #[GetArgument]
    private readonly string $entityType,
  ) {}

  /**
   * Immutable setter. Restricts the bundles to be considered for field labels.
   *
   * @param array<string, mixed> $bundles
   *   Format: $[$bundle] = $_.
   *
   * @return static
   */
  public function withBundles(array $bundles): static {
    $clone = clone $this;
    $clone->entityFieldLabelLookup = $this->entityFieldLabelLookup->withEntityBundles(
      $this->entityType,
      $bundles,
    );
    return $clone;
  }

  public function idGetText(int|string $id): ?TextInterface {
    return $this->entityFieldLabelLookup->idGetText($this->entityType . '.' . $id);
  }

}
