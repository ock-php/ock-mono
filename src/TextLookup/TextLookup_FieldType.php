<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

/**
 * Lookup for field type labels.
 */
#[RegisterService('renderkit.text_lookup.field_type')]
class TextLookup_FieldType implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypeManager
   */
  public function __construct(
    #[DrupalService('plugin.manager.field.field_type')]
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
