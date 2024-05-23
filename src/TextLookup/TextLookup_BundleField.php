<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;

#[Service(self::class)]
class TextLookup_BundleField implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  private function __construct(
    #[GetService('entity_field.manager')]
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
