<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\renderkit\Util\FieldUtil;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

class Configurator_FieldFormatterSettings implements ConfiguratorInterface {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $fieldInfo;

  /**
   * @var string
   */
  private $formatterType;

  /**
   * @var array
   */
  private $formatterTypeInfo;

  /**
   * @param string $fieldName
   * @param string $formatterType
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function create($fieldName, $formatterType) {
    /* @see hook_field_formatter_info() */
    $formatterTypeInfo = field_info_formatter_types($formatterType);
    if (empty($formatterTypeInfo)) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'Could not find info for formatter type.');
    }
    if (!isset($formatterTypeInfo['field types'])) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'No field types defined for formatter type.');
    }
    $fieldInfo = field_info_field($fieldName);
    if (!isset($fieldInfo)) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'Field not found.');
    }
    if (!isset($fieldInfo['type'])) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'Field has no type.');
    }
    $fieldType = $fieldInfo['type'];
    if (!in_array($fieldType, $formatterTypeInfo['field types'], TRUE)) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'Field type not supported by formatter type.');
    }
    return new self($fieldName, $fieldInfo, $formatterType, $formatterTypeInfo);
  }

  /**
   * @param string $fieldName
   * @param array $fieldInfo
   * @param string $formatterType
   * @param array $formatterTypeInfo
   */
  public function __construct($fieldName, array $fieldInfo, $formatterType, array $formatterTypeInfo) {
    $this->fieldName = $fieldName;
    $this->fieldInfo = $fieldInfo;
    $this->formatterType = $formatterType;
    $this->formatterTypeInfo = $formatterTypeInfo;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    /* @see hook_field_formatter_settings_form() */
    $function = $this->formatterTypeInfo['module'] . '_field_formatter_settings_form';
    if (!function_exists($function)) {
      return array();
    }
    $settings = $this->confGetFormatterSettings($conf);
    $instance = FieldUtil::createFakeFieldInstance($this->fieldName, '_custom', $this->formatterType, $settings);
    $form = array();
    $form_state = array();
    $settings_form = $function($this->fieldInfo, $instance, '_custom', $form, $form_state);

    if (!count($settings_form)) {
      return array();
    }
    return $settings_form;
    # return array('settings' => $settings_form);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    $settings = $this->confGetFormatterSettings($conf);
    $instance = FieldUtil::createFakeFieldInstance($this->fieldName, '_custom', $this->formatterType, $settings);
    /* @see hook_field_formatter_settings_summary() */
    return module_invoke($this->formatterTypeInfo['module'], 'field_formatter_settings_summary', $this->fieldInfo, $instance, '_custom');
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->confGetFormatterSettings($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return array
   */
  private function confGetFormatterSettings($conf) {
    $settings = is_array($conf) ? $conf : array();
    # $settings = isset($conf['settings']) ? $conf['settings'] : array();
    return $settings + $this->formatterTypeInfo['settings'];
  }
}
