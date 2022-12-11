<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

#[RegisterService('ock.formula.field_type.flat')]
class Formula_FieldType_Flat implements Formula_FlatSelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManager $fieldTypePluginManager
   */
  public function __construct(
    #[DrupalService('plugin.manager.field.field_type')]
    private readonly FieldTypePluginManagerInterface $fieldTypePluginManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $definitions = $this->fieldTypePluginManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = DrupalText::fromVar($definition['label'] ?? $id);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    try {
      $definition = $this->fieldTypePluginManager->getDefinition($id, false);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Unexpected exception', 0, $e);
    }
    if ($definition === NULL) {
      return NULL;
    }
    return DrupalText::fromVarOr($definition['label'] ?? NULL, $id);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    try {
      return NULL !== $this->fieldTypePluginManager->getDefinition($id, false);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Unexpected exception', 0, $e);
    }
  }

}
