<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

class CfSchema_EntityType implements CfSchema_OptionsInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

  /**
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(EntityTypeRepositoryInterface $entityTypeRepository) {
    $this->entityTypeRepository = $entityTypeRepository;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    return ['' => $options];
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    return isset($options[$id])
      ? $options[$id]
      : NULL;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    return isset($options[$id]);
  }
}
