<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * A display handler that uses a different display for specific entity types or
 * bundles.
 */
class EntityDisplay_EtBundleSwitcher extends EntityDisplay_EtSwitcher {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[][]
   *   Format: $[$entityType][$bundleName] = $displayHandler
   */
  private array $typeBundleDisplays = [];

  /**
   * Sets the display handler that will be used for the given bundle.
   *
   * @param string $entityType
   * @param string $bundle
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $display
   *
   * @return $this
   */
  public function entityTypeBundleSetDisplay(string $entityType, string $bundle, EntityDisplayInterface $display): self {
    $this->typeBundleDisplays[$entityType][$bundle] = $display;

    return $this;
  }

  /**
   * @param string $entityTypeId
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  protected function typeBuildEntities(string $entityTypeId, array $entities): array {

    if (!isset($this->typeBundleDisplays[$entityTypeId])) {
      return parent::typeBuildEntities($entityTypeId, $entities);
    }

    $bundleDisplays = $this->typeBundleDisplays[$entityTypeId];

    $entitiesByBundle = [];
    $remainingEntities = $entities;
    foreach ($entities as $delta => $entity) {
      $bundleName = $entity->bundle();
      if (isset($bundleDisplays[$bundleName])) {
        unset($remainingEntities[$delta]);
        $entitiesByBundle[$bundleName][$delta] = $entity;
      }
    }

    $buildsUnsorted = parent::buildEntities($remainingEntities);
    foreach ($entitiesByBundle as $bundleName => $bundleEntities) {
      $buildsUnsorted += $bundleDisplays[$bundleName]->buildEntities($bundleEntities);
    }

    $builds = [];
    foreach ($entities as $delta => $entity) {
      if (isset($buildsUnsorted[$delta])) {
        $builds[$delta] = $buildsUnsorted[$delta];
      }
    }

    return $builds;
  }

}
