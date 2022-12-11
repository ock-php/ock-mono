<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Drupal\ock\Attribute\DI\RegisterService;

/**
 * Main entry point for field label lookup.
 *
 * This class only contains static factories, the main logic is elsewhere.
 */
#[RegisterService]
class TextLookup_EntityFormMode extends TextLookup_CombinedLabelBase {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\TextLookup\TextLookup_EntityType $entityTypeLabelLookup
   * @param callable(string): \Drupal\renderkit\TextLookup\TextLookup_EntityId $getEntityLabelLookup
   */
  public function __construct(
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
    #[GetService(TextLookup_EntityId::MAP_SERVICE_ID)]
    callable $getEntityLabelLookup,
  ) {
    parent::__construct(
      $entityTypeLabelLookup,
      $getEntityLabelLookup('entity_form_mode'),
    );
  }

}
