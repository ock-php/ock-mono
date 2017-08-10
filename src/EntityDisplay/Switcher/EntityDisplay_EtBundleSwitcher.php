<?php

namespace Drupal\renderkit8\EntityDisplay\Switcher;

use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

/**
 * A display handler that uses a different display for specific entity types or
 * bundles.
 */
class EntityDisplay_EtBundleSwitcher extends EntityDisplay_EtSwitcher {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[][]
   *   Format: $[$entityType][$bundleName] = $displayHandler
   */
  private $typeBundleDisplays = [];

  /**
   * Sets the display handler that will be used for the given bundle.
   *
   * @param string $entityType
   * @param string $bundle
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $display
   *
   * @return $this
   */
  public function entityTypeBundleSetDisplay($entityType, $bundle, EntityDisplayInterface $display) {
    $this->typeBundleDisplays[$entityType][$bundle] = $display;

    return $this;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {
    if (isset($this->typeBundleDisplays[$entityType])) {
      $bundleKey = $this->entityTypeGetBundleKey($entityType);
      if (NULL !== $bundleKey) {
        $builds = [];
        $entitiesByBundle = [];
        $bundleDisplays = $this->typeBundleDisplays[$entityType];
        foreach ($entities as $delta => $entity) {
          if (isset($entity->$bundleKey)) {
            $bundleName = $entity->$bundleKey;
            if (isset($bundleDisplays[$bundleName])) {
              unset($entities[$delta]);
              $entitiesByBundle[$bundleName][$delta] = $entity;
            }
          }
          $builds[$delta] = [];
        }
        $buildsUnsorted = parent::buildEntities($entities);
        foreach ($entitiesByBundle as $bundleName => $bundleEntities) {
          $buildsUnsorted += $bundleDisplays[$bundleName]->buildEntities($bundleEntities);
        }
        foreach ($buildsUnsorted as $delta => $build) {
          $builds[$delta] = $build;
        }
        return $builds;
      }
    }
    return parent::buildEntities($entities);
  }

  /**
   * @param string $entity_type
   *
   * @return string
   */
  private function entityTypeGetBundleKey($entity_type) {
    $info = entity_get_info($entity_type);
    return empty($info['entity keys']['bundle'])
      ? NULL
      : $info['entity keys']['bundle'];
  }

}
