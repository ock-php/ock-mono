<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay\Field;

use Drupal\renderkit\EntityDisplay\FieldEntityDisplay;
use Drupal\renderkit\FieldUtil;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class FieldFormatterSettingsEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var string
   */
  private $formatterType;

  /**
   * @var array
   */
  private $formatterTypeInfo;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $fieldInfo;

  /**
   * @param string $formatterType
   * @param array $formatterTypeInfo
   * @param string $fieldName
   * @param array $fieldInfo
   */
  function __construct($formatterType, array $formatterTypeInfo, $fieldName, array $fieldInfo) {
    $this->formatterType = $formatterType;
    $this->formatterTypeInfo = $formatterTypeInfo;
    $this->fieldName = $fieldName;
    $this->fieldInfo = $fieldInfo;
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {
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
    return array(
      'settings' => $settings_form,
    );
  }

  /**
   * @param array $conf
   *   Plugin configuration.
   * @param string $pluginLabel
   *   Label from the plugin definition.
   *
   * @return string|null
   */
  function confGetSummary(array $conf, $pluginLabel = NULL) {
    $settings = $this->confGetFormatterSettings($conf);
    $instance = FieldUtil::createFakeFieldInstance($this->fieldName, '_custom', $this->formatterType, $settings);
    return module_invoke($this->formatterTypeInfo['module'], 'field_formatter_settings_summary', $this->fieldInfo, $instance, '_custom');
  }

  /**
   * Gets a handler object that does the business logic, or null, or dummy
   * object.
   *
   * @param array $conf
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return object|null
   *   The handler object, or a dummy handler object, or NULL.
   *   Plugins should return handlers of a specific type, but they are not
   *   technically required to do this. This is why an additional check should
   *   be performed for everything returned from a plugin.
   *
   * @throws \Exception
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayPlugin
   */
  function confGetHandler(array $conf) {
    $formatterSettings = $this->confGetFormatterSettings($conf);
    $display = array(
      'type' => $this->formatterType,
      'settings' => $formatterSettings,
    );
    return new FieldEntityDisplay($this->fieldName, $display);
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  private function confGetFormatterSettings(array $conf) {
    $settings = isset($conf['settings'])
      ? $conf['settings']
      : array();
    return $settings + $this->formatterTypeInfo['settings'];
  }

}
