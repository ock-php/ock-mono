<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

class Formula_EntityType implements Formula_FlatSelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

  /**
   * @return \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  public static function createOptionsFormula() {
    return new Formula_Select_FromFlatSelect(self::create());
  }

  /**
   * @return self
   */
  public static function create(): self {
    /** @var \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository */
    $entityTypeRepository = \Drupal::service('entity_type.repository');
    return new self($entityTypeRepository);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(EntityTypeRepositoryInterface $entityTypeRepository) {
    $this->entityTypeRepository = $entityTypeRepository;
  }

  /**
   * @return string[]
   */
  public function getOptions(): array {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    asort($options);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    return $options[$id] ?? null;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    $options = $this->entityTypeRepository->getEntityTypeLabels();

    return isset($options[$id]);
  }
}
