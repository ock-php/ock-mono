<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\ock\DrupalText;

class Formula_EntityBundleName implements Formula_FlatSelectInterface {

  /**
   * @param string $entityType
   *
   * @return self
   */
  public static function create(string $entityType): self {
    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo */
    $bundleInfo = \Drupal::service('entity_type.bundle.info');
    return new self($bundleInfo, $entityType);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo
   * @param string $entityType
   */
  public function __construct(
    private readonly EntityTypeBundleInfoInterface $bundleInfo,
    private readonly string $entityType,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $options = [];
    foreach ($this->bundleInfo->getBundleInfo($this->entityType) as $bundle => $info) {
      // Don't use opt groups, put everything at the top-level.
      $options[$bundle] = DrupalText::fromVar($info['label'] ?? $bundle);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    $bundle = $this->bundleInfo->getBundleInfo($this->entityType)[$id] ?? NULL;
    if ($bundle === NULL) {
      return NULL;
    }
    return DrupalText::fromVar($bundle['label'] ?? $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return NULL !== ($this->bundleInfo->getBundleInfo($this->entityType)[$id] ?? NULL);
  }

}
