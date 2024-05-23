<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

class Formula_EntityType_WithGroupLabels implements Formula_DrupalSelectInterface {

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
  public function __construct(
    private readonly EntityTypeRepositoryInterface $entityTypeRepository,
  ) {}

  /**
   * @return string[]
   */
  public function getGroupedOptions(): array {

    $etm = \Drupal::entityTypeManager();

    $grouped = [
      'content' => [],
      'config' => [],
      'other' => [],
    ];

    foreach ($etm->getDefinitions() as $entityTypeId => $definition) {

      $class = $definition->getClass();

      if (is_a($class, ContentEntityInterface::class, TRUE)) {
        $groupId = 'content';
      }
      elseif (is_a($class, ConfigEntityInterface::class, TRUE)) {
        $groupId = 'config';
      }
      else {
        $groupId = 'other';
      }

      $grouped[$groupId][$entityTypeId] = (string)$definition->getLabel();
    }

    $groupedOptions = [];
    foreach ($grouped as $groupId => $labelsInGroup) {
      asort($labelsInGroup);
      $groupLabel = match ($groupId) {
        'content' => (string) t('Content entity types'),
        'config' => (string) t('Config entity types'),
        default => (string) t('Other'),
      };
      $groupedOptions[$groupLabel] = $labelsInGroup;
    }

    return $groupedOptions;
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
