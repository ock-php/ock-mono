<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

#[RegisterService('renderkit.text_lookup.base_field')]
class TextLookup_BaseField implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $entityTypeId
   */
  public function __construct(
    #[DrupalService('entity_field.manager')]
    private readonly EntityFieldManagerInterface $entityFieldManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    [$entityType, $baseFieldName] = explode('.', $id);
    $label = ($this->entityFieldManager->getBaseFieldDefinitions(
      $entityType,
    )[$baseFieldName] ?? NULL)?->getLabel();
    if ($label === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($label);
  }

}
