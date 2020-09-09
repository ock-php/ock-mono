<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_Select_FromFlatSelect;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

class CfSchema_EntityType implements CfSchema_FlatSelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

  /**
   * @return \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface
   */
  public static function createOptionsSchema() {
    return new CfSchema_Select_FromFlatSelect(self::create());
  }

  /**
   * @return self
   */
  public static function create() {
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
