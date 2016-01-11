<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConf;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;
use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\renderkit\ConfiguratorMap\ConfiguratorMap_FieldDisplaySettings;
use Drupal\renderkit\EnumMap\EnumMap_FieldName;
use Drupal\renderkit\IdValueToValue\IdValueToValue_FieldDisplaySettingsToFieldEntityDisplay;

class Configurator_EntityDisplay_FieldWithFormatter extends Configurator_IdConf {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $configurators = array();

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param null $entityType
   * @param null $bundleName
   *
   * @return static
   */
  static function create($entityType = NULL, $bundleName = NULL) {
    $legend = new EnumMap_FieldName(NULL, $entityType, $bundleName);
    $configuratorMap = new ConfiguratorMap_FieldDisplaySettings();
    $idValueToValue = new IdValueToValue_FieldDisplaySettingsToFieldEntityDisplay();
    return (new self($legend, $configuratorMap, $idValueToValue))
      ->withKeys('field', 'display')
      ->withIdLabel(t('Field'));
  }

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMapInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface $idValueToValue
   */
  function __construct(
    LegendInterface $legend,
    ConfiguratorMapInterface $idToConfigurator,
    IdValueToValueInterface $idValueToValue
  ) {
    parent::__construct($legend, $idToConfigurator, $idValueToValue);
  }

  /**
   * @param string $delta
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param string $label
   */
  function addConfigurator($delta, ConfiguratorInterface $configurator, $label) {
    if (isset($this->labels[$delta])) {
      throw new \InvalidArgumentException('Delta already occupied.');
    }
    $this->configurators[$delta] = $configurator;
    $this->labels[$delta] = $label;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   *   A form element(s) array.
   */
  function confGetForm($conf, $label) {
    $form = parent::confGetForm($conf, $label);
    foreach ($this->configurators as $delta => $configurator) {
      $deltaConf = isset($conf[$delta]) ? $conf[$delta] : NULL;
      $form[$delta] = $configurator->confGetForm($deltaConf, $this->labels[$delta]);
    }
    return $form;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    if (!is_array($conf) || !count($conf)) {
      $conf = array();
    }
    $group = $summaryBuilder->startGroup();
    foreach ($this->configurators as $delta => $configurator) {
      $deltaConf = array_key_exists($delta, $conf) ? $conf[$delta] : NULL;
      $group->addSetting($this->labels[$delta], $configurator, $deltaConf);
    }
    return $group->buildSummary();
  }

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  function confGetValue($conf) {
    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = array();
    }
    $values = array();
    foreach ($this->configurators as $delta => $configurator) {
      if (array_key_exists($delta, $conf)) {
        $value = $configurator->confGetValue($conf[$delta]);
      }
      else {
        $value = $configurator->confGetValue(NULL);
      }
      $values[$delta] = $value;
      if ($value instanceof BrokenValueInterface) {
        return new BrokenValue($this, get_defined_vars(), 'Value for $delta is broken.');
      }
    }
    return $values;
  }

}
