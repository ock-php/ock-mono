<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityViewModeInterface;
use Drupal\ock\DrupalText;
use Drupal\renderkit\TextLookup\TextLookup_EntityType;
use Ock\DID\Attribute\Parameter\CallService;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Text\TextInterface;

#[Service(self::class)]
class Formula_EntityViewModeX implements Formula_SelectInterface {

  private ?string $targetEntityType = NULL;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\Entity\ConfigEntityStorageInterface $storage
   */
  public function __construct(
    #[CallService(args: ['entity_view_mode'])]
    private readonly ConfigEntityStorageInterface $storage,
    #[GetService]
    TextLookup_EntityType $entityTypeLabelLookup,
  ) {}

  /**
   * @param string $targetEntityType
   *
   * @return static
   */
  public function withTargetEntityType(string $targetEntityType): static {
    $clone = clone $this;
    $clone->targetEntityType = $targetEntityType;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(int|string $id): bool {
    return NULL !== $this->idGetViewMode($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(int|string $id): ?TextInterface {
    return DrupalText::fromEntityOrNull($this->idGetViewMode($id));
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    /** @var \Drupal\Core\Config\Entity\ConfigEntityInterface[] $entities */
    $entities = $this->targetEntityType
      ? $this->storage->loadByProperties([
        'targetEntityType' => $this->targetEntityType,
      ])
      : $this->storage->loadMultiple();
    $map = [];
    foreach ($entities as $entity) {
      $map[$entity->id()] = (string) $entity->get('targetEntityType');
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return NULL;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\Core\Config\Entity\ConfigEntityInterface|null
   */
  private function idGetViewMode(string $id): ?EntityViewModeInterface {
    $mode = $this->storage->load($id);
    if (!$mode instanceof ConfigEntityInterface) {
      return NULL;
    }
    if ($this->targetEntityType !== NULL
      && $mode->get('targetEntityType') !== $this->targetEntityType) {
      return NULL;
    }
    return $mode;
  }

}
