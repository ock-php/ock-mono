<?php

declare(strict_types=1);

namespace Drupal\ock\TextLookup;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Drupal\Core\Field\FieldTypePluginManager;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\ock\DrupalText;
use Psr\Container\ContainerInterface;

/**
 * Lookup for field type labels.
 */
class TextLookup_FieldType implements TextLookupInterface {

  /**
   * @var \Drupal\Core\Field\FieldTypePluginManager
   */
  private FieldTypePluginManager $fieldTypeManager;

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return self
   */
  public static function fromContainer(ContainerInterface $container): self {
    return new self(
      $container->get('plugin.manager.field.field_type'));
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypeManager
   */
  public function __construct(FieldTypePluginManagerInterface $fieldTypeManager) {
    $this->fieldTypeManager = $fieldTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {
    $definitions = $this->fieldTypeManager->getDefinitions();
    $definitions = array_intersect_key($definitions, $ids_map);
    $labels = [];
    foreach ($definitions as $id => $definition) {
      $labels[$id] = DrupalText::fromVar($definition['label'] ?? NULL)
        ?? Text::s($id);
    }
    return $labels;
  }

}
