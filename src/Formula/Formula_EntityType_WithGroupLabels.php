<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

class Formula_EntityType_WithGroupLabels implements Formula_SelectInterface {

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

      switch ($groupId) {

        case 'content':
          $groupLabel = (string)t('Content entity types');
          break;

        case 'config':
          $groupLabel = (string)t('Config entity types');
          break;

        default:
          $groupLabel = (string)t('Other');
      }

      $groupedOptions[$groupLabel] = $labelsInGroup;
    }

    return $groupedOptions;
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
