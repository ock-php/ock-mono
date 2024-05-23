<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\ock\DrupalText;

/**
 * Lookup for field type labels.
 */
#[Service(self::class)]
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
