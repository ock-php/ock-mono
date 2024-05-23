<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\DID\Attribute\Parameter\CallService;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\ock\DrupalText;

class Formula_ViewsViewId implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   */
  public function __construct(
    #[CallService(args: ['view'])]
    private readonly EntityStorageInterface $storage,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    $map = [];
    foreach ($this->storage->loadMultiple() as $view) {
      // Use top-level optgroup for all views.
      $map[$view->id()] = '';
    }
    return $map;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    if (NULL === $view = $this->storage->load($id)) {
      return NULL;
    }
    return DrupalText::fromEntity($view);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== $this->storage->load($id);
  }

  /**
   * @param int|string $groupId
   *
   * @return \Ock\Ock\Text\TextInterface|null
   */
  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return NULL;
  }

}
