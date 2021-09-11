<?php

declare(strict_types=1);

namespace Drupal\ock\TextLookup;

use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\ock\DrupalText;

class TextLookup_Field_BaseField implements TextLookupInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private EntityFieldManagerInterface $entityFieldManager;

  private string $entityTypeId;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param string $entityTypeId
   */
  public function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    string $entityTypeId
  ) {
    $this->entityFieldManager = $entityFieldManager;
    $this->entityTypeId = $entityTypeId;
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {

    $definitions = $this->entityFieldManager->getBaseFieldDefinitions(
      $this->entityTypeId);

    $definitions = array_intersect_key($definitions, $ids_map);

    $labels = [];
    foreach ($definitions as $field_name => $definition) {
      $labels[$field_name] = DrupalText::fromVar(
        $definition->getLabel());
    }

    return $labels;
  }

}
