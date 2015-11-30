<?php

namespace Drupal\renderkit\UniPlugin\FieldFormatter;

use Drupal\renderkit\FieldUtil;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class DefaultFieldFormatterPlugin extends ConfigurableUniPluginBase {

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string|null $entityType
   * @param string|null $bundleName
   */
  function __construct($entityType = NULL, $bundleName = NULL) {
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {

    $currentFieldName = isset($conf['field_name'])
      ? $conf['field_name']
      : NULL;

    $availableFieldsInfo = $this->getAvailableFieldsInfo();

    if (1
      && NULL !== $currentFieldName
      && array_key_exists($currentFieldName, $availableFieldsInfo)
    ) {
      $currentFieldInfo = $availableFieldsInfo[$currentFieldName];
    }
    else {
      $currentFieldName = NULL;
      $currentFieldInfo = array();
    }

    $form = array();

    $fieldNamesByType = array();
    foreach ($availableFieldsInfo as $fieldName => $fieldInfo) {
      $fieldType = $fieldInfo['type'];
      $fieldNamesByType[$fieldType][$fieldName] = $fieldInfo['label'];
    }

    $form['field'] = array(
      '#type' => 'select',
      '#title' => t('Field'),
      '#default_value' => $currentFieldName,
      '#options' => $fieldNamesByType,
    );

    if (1
      && NULL !== $currentFieldName
      && ($fieldInfo = field_info_field($currentFieldName))
    ) {
      $formatterValues = isset($conf['formatter'])
        ? $conf['formatter']
        : array();
      $form['formatter'] = $this->fieldGetFormatterForm($currentFieldName, $currentFieldInfo, $formatterValues);
    }
    else {
      $form['formatter'] = array();
    }
  }

  /**
   * @return array[]
   */
  private function getAvailableFieldsInfo() {

    if (NULL !== $this->entityType) {
      return field_info_fields();
    }

    $availableFieldsInfo = array();
    foreach (field_info_fields() as $fieldName => $fieldInfo) {
      if (!isset($fieldInfo['bundles'][$this->entityType])) {
        continue;
      }
      if (NULL !== $this->bundleName && !in_array($this->bundleName, $fieldInfo['bundles'][$this->entityType])) {
        continue;
      }
      $availableFieldsInfo[$fieldName] = $fieldInfo;
    }

    return $availableFieldsInfo;
  }

  /**
   * @param string $fieldName
   * @param array $fieldInfo
   * @param array $values
   *
   * @return array
   */
  private function fieldGetFormatterForm($fieldName, array $fieldInfo, array $values) {

    $availableFormatterTypes = $this->fieldTypeGetAvailableFormatterTypes($fieldInfo['type']);

    $currentFormatterTypeName = isset($values['type'])
      ? $values['type']
      : NULL;

    if (1
      && NULL !== $currentFormatterTypeName
      && array_key_exists($currentFormatterTypeName, $availableFormatterTypes)
    ) {
      $currentFormatterTypeInfo = $availableFormatterTypes[$currentFormatterTypeName];
    }
    else {
      $currentFormatterTypeName = NULL;
      $currentFormatterTypeInfo = array();
    }

    $options = array();
    foreach ($availableFormatterTypes as $formatterTypeName => $formatterTypeDefinition) {
      $module = $formatterTypeDefinition['module'];
      $options[$module][$formatterTypeName] = $formatterTypeDefinition['label'];
    }

    $form = array();

    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Formatter'),
      '#default_value' => $currentFormatterTypeName,
    );


    if (NULL !== $currentFormatterTypeName) {
      // @todo Really??
      # $formatterSettings = $this->confGetFormatterSettings($values, $currentFormatterTypeInfo);
      $formatterSettings = array();
      $form['settings'] = $this->fieldFormatterGetSettingsForm($fieldName, $fieldInfo, $currentFormatterTypeInfo, $formatterSettings);
    }
    else {
      $form['settings'] = array();
    }

    return $form;
  }

  /**
   * @param string $fieldType
   *
   * @return array[]
   */
  private function fieldTypeGetAvailableFormatterTypes($fieldType) {
    $availableFormatterTypes = array();
    foreach (field_info_formatter_types() as $formatterTypeName => $formatterTypeDefinition) {
      if (!in_array($fieldType, $formatterTypeDefinition['field types'])) {
        continue;
      }
      $availableFormatterTypes[$formatterTypeName] = $formatterTypeDefinition;
    }
    return $availableFormatterTypes;
  }

  /**
   * @param string $fieldName
   * @param array $fieldInfo
   * @param array $formatterTypeInfo
   * @param array $formatterSettings
   *
   * @return array
   */
  private function fieldFormatterGetSettingsForm($fieldName, array $fieldInfo, array $formatterTypeInfo, /** @noinspection PhpUnusedParameterInspection */
    array $formatterSettings) {

    /* @see hook_field_formatter_settings_form() */
    $function = $formatterTypeInfo['module'] . '_field_formatter_settings_form';
    if (!function_exists($function)) {
      return array();
    }
    // @todo Really??
    # $settings = $this->confGetFormatterSettings($conf);
    $settings = array();
    // @todo Really??
    $formatterTypeName = $formatterTypeInfo['name'];
    $instance = FieldUtil::createFakeFieldInstance($fieldName, '_custom', $formatterTypeName, $settings);
    $form = array();
    $form_state = array();
    $settings_form = $function($fieldInfo, $instance, '_custom', $form, $form_state);
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
    // TODO: Implement confGetSummary() method.
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
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  function confGetHandler(array $conf = NULL) {
    // TODO: Implement confGetHandler() method.
  }

}
