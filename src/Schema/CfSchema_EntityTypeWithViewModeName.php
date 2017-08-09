<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

/**
 * Schema for a string consisting of entity type plus view mode name, such
 * as 'node:teaser' or 'taxonomy_term:full'.
 */
class CfSchema_EntityTypeWithViewModeName implements CfSchema_OptionsInterface {

  /**
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  private $entityDisplayRepository;

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   * @param string|null $entityType
   */
  public function __construct(
    EntityDisplayRepositoryInterface $entityDisplayRepository,
    EntityTypeRepositoryInterface $entityTypeRepository,
    $entityType = NULL
  ) {
    $this->entityDisplayRepository = $entityDisplayRepository;
    $this->entityTypeRepository = $entityTypeRepository;
    $this->entityType = $entityType;
  }

  /**
   * @return mixed[]
   */
  public function getGroupedOptions() {

    $entityTypeLabels = $this->entityTypeRepository->getEntityTypeLabels();

    $options = [];
    if (NULL === $this->entityType) {
      /** @var array[] $viewModes */
      foreach ($this->entityDisplayRepository->getAllViewModes() as $entityType => $viewModes) {
        if (!isset($entityTypeLabels[$entityType])) {
          continue;
        }
        $entityTypeLabel = $entityTypeLabels[$entityType];
        foreach ($viewModes as $mode => $settings) {
          // @todo Find a "type label".
          $options[$entityTypeLabel][$entityType . ':' . $mode] = $settings['label'];
        }
      }
    }
    else {
      $entityTypeLabel = $entityTypeLabels[$this->entityType];
      foreach ($this->entityDisplayRepository->getViewModeOptions($this->entityType) as $mode => $label) {
        // @todo Find a "type label".
        $options[$entityTypeLabel][$this->entityType . ':' . $mode] = $label;
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    list($type, $mode) = explode(':', $id . ':');

    if ('' === $type || '' === $mode) {
      return NULL;
    }

    if (NULL !== $this->entityType && $type !== $this->entityType) {
      return NULL;
    }

    $entityTypeLabels = $this->entityTypeRepository->getEntityTypeLabels();
    if (!isset($entityTypeLabels[$type])) {
      return NULL;
    }
    $entityTypeLabel = $entityTypeLabels[$type];

    $viewModeLabels = $this->entityDisplayRepository->getViewModeOptions($type);
    if (!isset($viewModeLabels[$mode])) {
      return NULL;
    }
    $viewModeLabel = $viewModeLabels[$mode];

    return $entityTypeLabel . ': ' . $viewModeLabel;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    list($type, $mode) = explode(':', $id . ':');

    if ('' === $type || '' === $mode) {
      return FALSE;
    }

    if (NULL !== $this->entityType && $type !== $this->entityType) {
      return FALSE;
    }

    $entityTypeLabels = $this->entityTypeRepository->getEntityTypeLabels();
    if (!isset($entityTypeLabels[$type])) {
      return FALSE;
    }

    $viewModes = $this->entityDisplayRepository->getViewModes($type);
    if (!isset($viewModes[$mode])) {
      return FALSE;
    }

    return TRUE;
  }
}
