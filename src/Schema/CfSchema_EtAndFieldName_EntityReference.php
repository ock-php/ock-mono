<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_OptionsBase;

class CfSchema_EtAndFieldName_EntityReference extends CfSchema_Proxy_Cache_OptionsBase {

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

    $cacheId = 'renderkit:schema:et_and_field_name:entity_reference';

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
  protected function getGroupedOptions() {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    # $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    # $ftm = \Drupal::service('plugin.manager.field.field_type');

    # $mapByFieldType = $efm->getFieldMapByFieldType();

    return [];
  }
}
