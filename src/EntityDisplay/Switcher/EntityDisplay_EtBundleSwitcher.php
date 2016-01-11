<?php

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
  private $typeBundleDisplays = array();

  /**
   * Sets the display handler that will be used for the given bundle.
   *
   * @param string $entityType
   * @param string $bundle
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $display
   *
   * @return $this
   */
  function entityTypeBundleSetDisplay($entityType, $bundle, EntityDisplayInterface $display) {
    $this->typeBundleDisplays[$entityType][$bundle] = $display;

    return $this;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entityType, array $entities) {
    if (isset($this->typeBundleDisplays[$entityType])) {
      $bundleKey = $this->entityTypeGetBundleKey($entityType);
      if (isset($bundleKey)) {
        $builds = array();
        $entitiesByBundle = array();
        $bundleDisplays = $this->typeBundleDisplays[$entityType];
        foreach ($entities as $delta => $entity) {
          if (isset($entity->$bundleKey)) {
            $bundleName = $entity->$bundleKey;
            if (isset($bundleDisplays[$bundleName])) {
              unset($entities[$delta]);
              $entitiesByBundle[$bundleName][$delta] = $entity;
            }
          }
          $builds[$delta] = array();
        }
        $buildsUnsorted = parent::buildEntities($entityType, $entities);
        foreach ($entitiesByBundle as $bundleName => $bundleEntities) {
          $buildsUnsorted += $bundleDisplays[$bundleName]->buildEntities($entityType, $bundleEntities);
        }
        foreach ($buildsUnsorted as $delta => $build) {
          $builds[$delta] = $build;
        }
        return $builds;
      }
    }
    return parent::buildEntities($entityType, $entities);
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
