<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

class Formula_FieldFormatterId implements Formula_DrupalSelectInterface {

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private $formatterPluginManager;

  /**
   * @var string
   */
  private $fieldTypeName;

  /**
   * @param string $fieldTypeName
   *
   * @return self
   */
  public static function create($fieldTypeName): self {

    /* @var \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager */
    $formatterPluginManager = \Drupal::service('plugin.manager.field.formatter');

    return new self($formatterPluginManager, $fieldTypeName);
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param string $fieldTypeName
   */
  public function __construct(FormatterPluginManager $formatterPluginManager, $fieldTypeName) {
    $this->formatterPluginManager = $formatterPluginManager;
    $this->fieldTypeName = $fieldTypeName;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = $this->formatterPluginManager->getOptions($this->fieldTypeName);

    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {

    if (NULL === $definition = $this->idGetDefinition($id)) {
      return NULL;
    }

    return $definition['label'];
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    return NULL !== $this->idGetDefinition($id);
  }

  /**
   * @param string $formatterTypeName
   *
   * @return array|null
   *   Field formatter definition, or NULL if not found.
   */
  private function idGetDefinition($formatterTypeName): ?array {

    try {
      $definition = $this->formatterPluginManager->getDefinition(
        $formatterTypeName,
        FALSE);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    if (NULL === $definition) {
      return NULL;
    }

    if (!\in_array($this->fieldTypeName, $definition['field_types'], TRUE)) {
      return NULL;
    }

    return $definition;
  }
}
