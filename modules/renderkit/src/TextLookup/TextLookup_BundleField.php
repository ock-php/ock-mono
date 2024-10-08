<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Service]
class TextLookup_BundleField implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  public function __construct(
    #[Autowire(service: 'entity_field.manager')]
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
