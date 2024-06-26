<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\DrupalText;
use Ock\Ock\Exception\FormulaException;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

class Formula_ConfigEntityIdGrouped implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\Entity\ConfigEntityStorageInterface $storage
   * @param string|false|null $groupBy
   * @param \Ock\Ock\TextLookup\TextLookupInterface|null $groupLabelLookup
   * @param array $properties
   */
  public function __construct(
    private readonly ConfigEntityStorageInterface $storage,
    private string|false|null $groupBy = null,
    private TextLookupInterface|null $groupLabelLookup = null,
    private array $properties = [],
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   *
   * @return self
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function createForEntityType(
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
  public function withProperty(string $name, mixed $value): static {
    $clone = clone $this;
    $clone->properties[$name] = $value;
    return $clone;
  }

  /**
   * @param string $name
   * @param \Ock\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   *
   * @return static
   */
  public function withGroupByProperty(string $name, TextLookupInterface $groupLabelLookup): static {
    $clone = clone $this;
    $clone->groupBy = $name;
    $clone->groupLabelLookup = $groupLabelLookup;
    return $clone;
  }

  /**
   * Immutable setter. Uses a prefix of the entity id as the group key.
   *
   * E.g. for a config entity id "node.teaser", the group id would be "node".
   *
   * @param \Ock\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   *   Label lookup for the group keys.
   *
   * @return static
   */
  public function withGroupByPrefix(TextLookupInterface $groupLabelLookup): static {
    $clone = clone $this;
    $clone->groupBy = FALSE;
    $clone->groupLabelLookup = $groupLabelLookup;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    /** @var \Drupal\Core\Config\Entity\ConfigEntityInterface[] $entities */
    $entities = $this->properties
      ? $this->storage->loadByProperties($this->properties)
      : $this->storage->loadMultiple();
    $map = [];
    if ($this->groupBy === NULL) {
      foreach ($entities as $entity) {
        $map[$entity->id()] = '';
      }
    }
    elseif ($this->groupBy === FALSE) {
      foreach ($entities as $entity) {
        $id = $entity->id();
        $map[$id] = explode('.', $id)[0];
      }
    }
    else {
      foreach ($entities as $entity) {
        $map[$entity->id()] = (string) ($entity->get($this->groupBy) ?: '');
      }
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return $this->groupLabelLookup?->idGetText($groupId);
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
