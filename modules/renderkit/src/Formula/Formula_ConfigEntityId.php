<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\DrupalText;
use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;
use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;

class Formula_ConfigEntityId implements Formula_FlatSelectInterface {

  const LOOKUP_SERVICE_ID = 'lookup.' . self::class;

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
    #[GetParametricService]
    private readonly ConfigEntityStorageInterface $storage,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @return \Closure(string): self
   */
  #[Service(self::LOOKUP_SERVICE_ID)]
  public static function createLookup(EntityTypeManagerInterface $entityTypeManager): \Closure {
    return function (string $entityTypeId) use ($entityTypeManager) {
      $storage = $entityTypeManager->getStorage($entityTypeId);
      \assert($storage instanceof ConfigEntityStorageInterface);
      return new self($storage);
    };
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
