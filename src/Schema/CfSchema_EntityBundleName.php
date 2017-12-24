<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;

class CfSchema_EntityBundleName implements CfSchema_SelectInterface {

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
   * @return \Drupal\renderkit8\Schema\CfSchema_EntityBundleName
   */
  public static function create($entityType) {
    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo */
    $bundleInfo = \Drupal::service('entity_type.bundle.info');
    return new self($bundleInfo, $entityType);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundleInfo
   * @param string $entityType
   */
  public function __construct(EntityTypeBundleInfoInterface $bundleInfo, $entityType) {
    $this->bundleInfo = $bundleInfo;
    $this->entityType = $entityType;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions(): array {

    $options = [];
    foreach ($this->bundleInfo->getBundleInfo($this->entityType) as $bundleName => $bundleInfo) {
      $options[$bundleName] = $bundleInfo['label'];
    }

    return ['' => $options];
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id): ?string {

    $bundles = $this->bundleInfo->getBundleInfo($this->entityType);

    return isset($bundles[$id])
      ? $bundles[$id]['label']
      : NULL;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    $bundles = $this->bundleInfo->getBundleInfo($this->entityType);

    return isset($bundles[$id]);
  }
}
