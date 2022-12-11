<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\DrupalText;

class Formula_ConfigEntityId implements Formula_FlatSelectInterface {

  const MAP_SERVICE_ID = 'renderkit.formula_map.config_entity_id';

  /**
   * @var array<string, mixed>
   */
  private array $properties = [];

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\Entity\ConfigEntityStorageInterface $storage
   */
  public function __construct(
    private readonly ConfigEntityStorageInterface $storage,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @return callable(string): self
   */
  #[RegisterService(self::MAP_SERVICE_ID)]
  public static function createMap(
    #[DrupalService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
  ): \Closure {
    return fn (string $entityTypeId) => self::create(
      $entityTypeManager,
      $entityTypeId,
    );
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  public static function create(
    EntityTypeManagerInterface $entityTypeManager,
    string $entityTypeId,
  ): self {
    try {
      $storage = $entityTypeManager->getStorage($entityTypeId);
    }
    catch (PluginException $e) {
      throw new FormulaException("Entity type '$entityTypeId' was not found.", 0, $e);
    }
    if (!$storage instanceof ConfigEntityStorageInterface) {
      throw new FormulaException("Entity type '$entityTypeId' does not seem to be a config entity type.");
    }
    return new self($storage);
  }

  /**
   * @param bool $status
   *
   * @return static
   */
  public function withStatus(bool $status): static {
    return $this->withProperty('status', $status);
  }

  /**
   * @param string $name
   * @param mixed $value
   *
   * @return static
   */
  protected function withProperty(string $name, mixed $value): static {
    $clone = clone $this;
    $clone->properties[$name] = $value;
    return $clone;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getOptions(): array {
    $entities = $this->properties
      ? $this->storage->loadByProperties($this->properties)
      : $this->storage->loadMultiple();
    $options = [];
    foreach ($entities as $entity) {
      $options[$entity->id()] = DrupalText::fromEntity($entity);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    return DrupalText::fromEntityOrNull($this->idGetEntity($id));
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== $this->idGetEntity($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\Core\Config\Entity\ConfigEntityInterface|null
   */
  private function idGetEntity(string $id): ?ConfigEntityInterface {
    $entity = $this->storage->load($id);
    if (!$entity instanceof ConfigEntityInterface) {
      return NULL;
    }
    foreach ($this->properties as $key => $value) {
      // @todo Does this really work?
      if ($entity->get($key) !== $value) {
        return NULL;
      }
    }
    return $entity;
  }
}
