<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\ock\Attribute\DI\RegisterService;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
class TextLookup_FieldName implements TextLookupInterface {

  const MAP_SERVICE_ID = 'renderkit.map.text_lookup.field_name';

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityField $entityFieldLabelLookup
   * @param string $entityType
   */
  public function __construct(
    private TextLookup_EntityField $entityFieldLabelLookup,
    private readonly string $entityType,
  ) {}

  /**
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityField $entityFieldLabelLookup
   *
   * @return callable(string): self
   */
  #[RegisterService(self::MAP_SERVICE_ID)]
  public static function createFormulaMap(
    #[GetService]
    TextLookup_EntityField $entityFieldLabelLookup,
  ): \Closure {
    return fn (string $entityTypeId) => new self(
      $entityFieldLabelLookup,
      $entityTypeId,
    );
  }

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
