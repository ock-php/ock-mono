<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay\Field;

use Drupal\renderkit\FieldUtil;
use Drupal\renderkit\FormUtil;
use Drupal\uniplugin\ConfiguredPlugin\ConfiguredUniPlugin;
use Drupal\uniplugin\UniPlugin\Broken\BrokenUniPlugin;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class FieldFormatterEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $fieldInfo;

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string $fieldName
   * @param array $fieldInfo
   * @param string|null $entityType
   * @param string|null $bundleName
   */
  function __construct($fieldName, array $fieldInfo, $entityType = NULL, $bundleName = NULL) {
    $this->fieldName = $fieldName;
    $this->fieldInfo = $fieldInfo;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @return string
   */
  function getFieldName() {
    return $this->getFieldName();
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {

    $availableFormatterTypes = FieldUtil::fieldTypeGetAvailableFormatterTypes($this->fieldInfo['type']);

    if (isset($conf['type']) && !array_key_exists($conf['type'], $availableFormatterTypes)) {
      $conf['type'] = NULL;
    }

    $formatterSettingsPlugin = $this->confGetFieldFormatterSettingsPlugin($conf);

    $options = array();
    foreach ($availableFormatterTypes as $formatterTypeName => $formatterTypeDefinition) {
      $module = $formatterTypeDefinition['module'];
      $options[$module][$formatterTypeName] = $formatterTypeDefinition['label'];
    }

    $form = array(
      # '#type' => 'renderkit_container',
      '#tree' => TRUE,
    );

    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Formatter'),
      '#default_value' => $formatterSettingsPlugin->getId(),
      '#options' => $options,
    );

    $form['settings'] = $formatterSettingsPlugin->getForm();

    FormUtil::onProcessBuildDependency($form);

    return $form;
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
    return $this->confGetFieldFormatterSettingsPlugin($conf)->getSummary();
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
  function confGetHandler(array $conf) {
    return $this->confGetFieldFormatterSettingsPlugin($conf)->getHandler();
  }

  /**
   * @param array $conf
   *
   * @return \Drupal\uniplugin\ConfiguredPlugin\ConfiguredUniPlugin
   */
  private function confGetFieldFormatterSettingsPlugin(array $conf) {

    $currentFormatterTypeName = isset($conf['type'])
      ? $conf['type']
      : NULL;

    if (NULL !== $currentFormatterTypeName) {
      $currentFormatterTypeInfo = field_info_formatter_types($currentFormatterTypeName);
      $formatterSettingsPlugin = new FieldFormatterSettingsEntityDisplayPlugin($currentFormatterTypeName, $currentFormatterTypeInfo, $this->fieldName, $this->fieldInfo);
    }
    else {
      $formatterSettingsPlugin = BrokenUniPlugin::createFromMessage('No formatter configured.');
    }

    $formatterSettings = array_key_exists('settings', $conf)
      ? $conf['settings']
      : array();

    return new ConfiguredUniPlugin($formatterSettingsPlugin, $formatterSettings, $currentFormatterTypeName);
  }

}
