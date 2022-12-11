<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

#[RegisterService('renderkit.text_lookup.bundle_field')]
class TextLookup_BundleField implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  private function __construct(
    #[DrupalService('entity_field.manager')]
    private readonly EntityFieldManagerInterface $entityFieldManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    [$entityType, $bundle, $fieldName] = explode('.', $id);
    $label = ($this->entityFieldManager->getFieldDefinitions(
      $entityType,
      $bundle,
    )[$fieldName] ?? NULL)?->getLabel();
    if ($label === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($label);
  }

}
