<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;
use Ock\DependencyInjection\Attribute\Service;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

#[Service]
class TextLookup_BaseField implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $entityTypeId
   */
  public function __construct(
    #[GetService('entity_field.manager')]
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
