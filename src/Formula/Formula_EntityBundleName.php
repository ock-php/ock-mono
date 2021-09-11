<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\ock\DrupalText;

class Formula_EntityBundleName implements Formula_FlatSelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  private $bundleInfo;

  /**
   * @var string
   */
  private $entityType;

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
  public function __construct(EntityTypeBundleInfoInterface $bundleInfo, string $entityType) {
    $this->bundleInfo = $bundleInfo;
    $this->entityType = $entityType;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $options = [];
    foreach ($this->bundleInfo->getBundleInfo($this->entityType) as $bundle => $info) {
      // Don't use opt groups, put everything at the top-level.
      $options[$bundle] = DrupalText::fromVar($info['label']);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    $bundles = $this->bundleInfo->getBundleInfo($this->entityType);

    if (!isset($bundles[$id])) {
      return NULL;
    }

    return DrupalText::fromVar($bundles[$id]['label']) ?? Text::s($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {

    $bundles = $this->bundleInfo->getBundleInfo($this->entityType);

    return isset($bundles[$id]);
  }

}
