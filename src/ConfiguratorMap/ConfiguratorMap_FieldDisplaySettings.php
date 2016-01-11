<?php

namespace Drupal\renderkit\ConfiguratorMap;

use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConf;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface;
use Drupal\renderkit\EnumMap\EnumMap_FieldFormatterType;
use Drupal\renderkit\IdValueToValue\IdValueToValue_FormatterSettingsToFieldDisplaySettings;

/**
 * Provides configurators for field display settings (formatter type + settings).
 */
class ConfiguratorMap_FieldDisplaySettings implements ConfiguratorMapInterface {

  /**
   * @param string $fieldName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function idGetConfigurator($fieldName) {
    $fieldInfo = field_info_field($fieldName);
    if (!isset($fieldInfo['type'])) {
      if (!isset($fieldInfo)) {
        return new BrokenConfigurator($this, get_defined_vars(), 'Unknown field.');
      }
      return new BrokenConfigurator($this, get_defined_vars(), 'Field without type.');
    }
    $legend = new EnumMap_FieldFormatterType($fieldInfo['type']);
    $configuratorMap = new ConfiguratorMap_FieldFormatterSettings($fieldName);
    $idValueToValue = new IdValueToValue_FormatterSettingsToFieldDisplaySettings();
    return (new Configurator_IdConf($legend, $configuratorMap, $idValueToValue))
      ->withKeys('type', 'settings')
      ->withIdLabel(t('Formatter'))
    ;
  }
}
