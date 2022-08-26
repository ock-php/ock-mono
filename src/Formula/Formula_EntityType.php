<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

class Formula_EntityType implements Formula_DrupalSelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

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
  public function getGroupedOptions(): array {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    asort($options);
    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    return $options[$id] ?? null;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    return isset($options[$id]);
  }

}
