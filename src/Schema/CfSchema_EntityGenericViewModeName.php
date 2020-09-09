<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;

/**
 * Schema for a view mode machine name that is used across entity types.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class CfSchema_EntityGenericViewModeName implements CfSchema_SelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  private $entityDisplayRepository;

  /**
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
   */
  public function __construct(EntityDisplayRepositoryInterface $entityDisplayRepository) {
    $this->entityDisplayRepository = $entityDisplayRepository;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions(): array {

    $modes = [];
    /** @var array[] $viewModes */
    foreach ($this->entityDisplayRepository->getAllViewModes() as $entityType => $viewModes) {
      /** @noinspection LoopWhichDoesNotLoopInspection */
      foreach ($viewModes as $viewModeName => $viewMode) {
        $modes[$viewModeName][] = $viewMode['label'] ?? $viewModeName;
      }
    }

    $options = [];
    foreach ($modes as $mode => $aliases) {
      $options[$mode] = implode(' / ', array_unique($aliases));
    }

    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?string {

    $aliases = [];
    foreach ($this->entityDisplayRepository->getAllViewModes() as $entityType => $viewModes) {
      if (isset($viewModes[$id]['label'])) {
        $aliases[] = $viewModes[$id]['label'];
      }
      elseif (isset($viewModes[$id])) {
        $aliases[] = $id;
      }
    }

    if ([] === $aliases) {
      return NULL;
    }

    return implode(' / ', array_unique($aliases));
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    foreach ($this->entityDisplayRepository->getAllViewModes() as $entityType => $viewModes) {
      if (isset($viewModes[$id])) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
