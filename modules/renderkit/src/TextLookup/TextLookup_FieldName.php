<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;
use Ock\DependencyInjection\Attribute\PrivateService;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[PrivateService]
class TextLookup_FieldName implements TextLookupInterface {

  const LOOKUP_SERVICE_ID = 'lookup.' . self::class;

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityField $entityFieldLabelLookup
   * @param string $entityType
   */
  public function __construct(
    private TextLookup_EntityField $entityFieldLabelLookup,
    #[GetParametricArgument(0)]
    private readonly string $entityType,
  ) {}

  #[Service(self::LOOKUP_SERVICE_ID)]
  public static function createLookup(
    TextLookup_EntityField $entityFieldLabelLookup,
  ): \Closure {
    return fn (string $entityTypeId): self => new self(
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
