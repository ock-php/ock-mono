<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;

class Formula_EtAndFieldName_EntityReference extends Formula_Proxy_Cache_SelectBase {

  /**
   * @var null|string
   */
  # private $entityType;

  /**
   * @var null|string
   */
  # private $bundle;

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   */
  public function __construct($entityType = NULL, $bundle = NULL) {

    # $this->entityType = $entityType;
    # $this->bundle = $bundle;

    $cacheId = 'renderkit:formula:et_and_field_name:entity_reference';

    if (NULL !== $entityType) {

      $cacheId .= ':' . $entityType;

      if (NULL !== $bundle) {
        $cacheId .= ':' . $bundle;
      }
    }
    else {
      $bundle = NULL;
    }

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    # $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    # $ftm = \Drupal::service('plugin.manager.field.field_type');

    # $mapByFieldType = $efm->getFieldMapByFieldType();

    return [];
  }
}
