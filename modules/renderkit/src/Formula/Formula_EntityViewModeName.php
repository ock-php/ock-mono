<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

/**
 * Formula for a view mode machine name for a given entity type.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class Formula_EntityViewModeName implements Formula_DrupalSelectInterface {

  /**
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
   * @param string $entityType
   */
  public function __construct(
    private readonly EntityDisplayRepositoryInterface $entityDisplayRepository,
    private readonly string $entityType,
  ) {}

  /**
   * @return string[][]
   */
  public function getGroupedOptions(): array {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return $options[$id] ?? null;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return isset($options[$id]);
  }

}
