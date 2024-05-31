<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;

#[Service(self::class)]
class Formula_FieldFormatterId implements Formula_DrupalSelectInterface {

  private ?string $fieldTypeName;

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   */
  public function __construct(
    #[GetService('plugin.manager.field.formatter')]
    private readonly FormatterPluginManager $formatterPluginManager,
  ) {}

  /**
   * @param string $fieldType
   *
   * @return static
   */
  public function withFieldType(string $fieldType): static {
    $clone = clone $this;
    $clone->fieldTypeName = $fieldType;
    return $clone;
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
  public function idGetLabel(string|int $id): string|MarkupInterface|null {

    if (NULL === $definition = $this->idGetDefinition($id)) {
      return NULL;
    }

    return $definition['label'];
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {

    return NULL !== $this->idGetDefinition($id);
  }

  /**
   * @param string $formatterTypeName
   *
   * @return array|null
   *   Field formatter definition, or NULL if not found.
   */
  private function idGetDefinition(string $formatterTypeName): ?array {

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
