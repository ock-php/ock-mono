<?php

namespace Drupal\renderkit\UniPlugin\FieldFormatter;

use Drupal\renderkit\FieldFormatter\FieldDisplayDefinition;
use Drupal\renderkit\FieldUtil;
use Drupal\uniplugin\UniPlugin\Broken\BrokenUniPlugin;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class FormatterPlugin extends ConfigurableUniPluginBase {

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
   * @param string $fieldName
   *   Contextual parameter.
   *
   * @return null|static
   */
  static function create($formatterType, $fieldName) {
    /* @see hook_field_formatter_info() */
    $formatterTypeInfo = field_info_formatter_types($formatterType);
    if (empty($formatterTypeInfo)) {
      return BrokenUniPlugin::createFromMessage('Could not find info for formatter type.');
    }
    if (!isset($formatterTypeInfo['field types'])) {
      return BrokenUniPlugin::createFromMessage('No field types defined for formatter type.');
    }
    $fieldInfo = field_info_field($fieldName);
    if (!isset($fieldInfo)) {
      return BrokenUniPlugin::createFromMessage('Field not found.');
    }
    if (!isset($fieldInfo['type'])) {
      return BrokenUniPlugin::createFromMessage('Field has no type.');
    }
    $fieldType = $fieldInfo['type'];
    if (!in_array($fieldType, $formatterTypeInfo['field types'])) {
      return BrokenUniPlugin::createFromMessage('Field type not supported by formatter type.');
    }
    return new static($formatterType, $formatterTypeInfo, $fieldName, $fieldInfo);
  }

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
   * @see \Drupal\uniplugin\Handler\BrokenUniHandlerInterface
   */
  function confGetHandler(array $conf = NULL) {
    $settings = $this->confGetFormatterSettings($conf);
    return FieldDisplayDefinition::create($this->formatterType, $settings);
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
