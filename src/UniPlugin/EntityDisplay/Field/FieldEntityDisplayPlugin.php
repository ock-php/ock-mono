<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay\Field;

use Drupal\renderkit\FieldUtil;
use Drupal\renderkit\FormUtil;
use Drupal\uniplugin\ConfiguredPlugin\ConfiguredUniPlugin;
use Drupal\uniplugin\UniPlugin\Broken\BrokenUniPlugin;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   label = "Field with formatter",
 *   id = "field"
 * )
 *
 * @see ctools_entity_field_content_type_content_type()
 *   A ctools plugin which does something similar.
 */
class FieldEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * FieldEntityDisplayPlugin constructor.
   *
   * @param string|null $entityType
   * @param string|null $bundleName
   */
  function __construct($entityType = NULL, $bundleName = NULL) {
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * Builds a settings form for the plugin configuration.
   *
   * @param array $conf
   *   Current configuration. Will be empty if not configured yet.
   *
   * @return array A sub-form array to configure the plugin.
   * A sub-form array to configure the plugin.
   * @see \views_handler::options_form()
   */
  function confGetForm(array $conf) {

    $formatterPlugin = $this->confGetFormatterPlugin($conf);

    $form = array();

    $form['field_name'] = array(
      '#title' => t('Field'),
      '#type' => 'select',
      '#options' => FieldUtil::etBundleGetFieldNameOptions($this->entityType, $this->bundleName),
      '#default_value' => $formatterPlugin->getId(),
      '#empty_value' => '',
    );

    $form['display'] = $formatterPlugin->getForm();

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
    return $this->confGetFormatterPlugin($conf)->getSummary();
  }

  /**
   * Gets a handler object that does the business logic.
   *
   * @param array $conf
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return object|\Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   *
   * @throws \Exception
   */
  function confGetValue(array $conf) {
    return $this->confGetFormatterPlugin($conf)->getHandler();
  }

  /**
   * @param array $conf
   *
   * @return \Drupal\uniplugin\ConfiguredPlugin\ConfiguredUniPlugin
   */
  private function confGetFormatterPlugin(array $conf) {

    $currentFieldName = isset($conf['field_name'])
      ? $conf['field_name']
      : NULL;

    $display = isset($conf['display'])
      ? $conf['display']
      : array();

    if (NULL !== $currentFieldName) {
      $currentFieldInfo = field_info_field($currentFieldName);
      if ($currentFieldInfo) {
        $plugin = new FieldFormatterEntityDisplayPlugin($currentFieldName, $currentFieldInfo, $this->entityType, $this->bundleName);
      }
      else {
        $plugin = BrokenUniPlugin::createFromMessage('Field not found.');
      }
    }
    else {
      $plugin = BrokenUniPlugin::createFromMessage('No field name configured.');
    }

    return new ConfiguredUniPlugin($plugin, $display, $currentFieldName);
  }
}
