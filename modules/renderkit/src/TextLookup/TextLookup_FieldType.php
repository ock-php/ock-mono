<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Ock\DependencyInjection\Attribute\Service;
use Drupal\ock\DrupalText;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

/**
 * Lookup for field type labels.
 */
#[Service]
class TextLookup_FieldType implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypeManager
   */
  public function __construct(
    #[GetService('plugin.manager.field.field_type')]
    private readonly FieldTypePluginManagerInterface $fieldTypeManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    $label = $this->fieldTypeManager->getDefinitions()[$id]['label'] ?? NULL;
    if ($label === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($label);
  }

}
