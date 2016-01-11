<?php

namespace Drupal\renderkit\ConfiguratorMap;

use Drupal\renderkit\Configurator\Configurator_FieldFormatterSettings;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface;

/**
 * Provides configurators for field formatter settings.
 */
class ConfiguratorMap_FieldFormatterSettings implements ConfiguratorMapInterface {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @param string $fieldName
   */
  function __construct($fieldName) {
    $this->fieldName = $fieldName;
  }

  /**
   * @param string|int $formatterTypeName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function idGetConfigurator($formatterTypeName) {
    return Configurator_FieldFormatterSettings::create($this->fieldName, $formatterTypeName);
  }
}
