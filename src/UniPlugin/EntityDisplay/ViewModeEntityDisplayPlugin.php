<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay;

use Drupal\renderkit\EntityDisplay\ViewModeEntityDisplay;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   id = "viewMode",
 *   label = @Translate("View mode")
 * )
 */
class ViewModeEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @param string $entityType
   */
  function __construct($entityType) {
    $this->entityType = $entityType;
  }

  /**
   * @param array $conf
   *
   * @return array
   * @see \entity_views_handler_field_entity::options_form()
   * @see \entity_views_plugin_row_entity_view::options_form()
   */
  function confGetForm(array $conf) {

    $entity_info = entity_get_info($this->entityType);
    $options = array();
    if (!empty($entity_info['view modes'])) {
      foreach ($entity_info['view modes'] as $mode => $settings) {
        $options[$mode] = $settings['label'];
      }
    }

    $form['view_mode'] = array(
      '#type' => 'select',
      '#title' => t('View mode'),
      // @todo Fetch the correct view modes depending on the entity type.
      // This requires that the entity type is known.
      '#options' => $options,
      '#default_value' => isset($conf['view_mode'])
        ? $conf['view_mode']
        : array(),
    );
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
    return NULL;
  }

  /**
   * @param array $conf
   *
   * @return null|\Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  function confGetHandler(array $conf = NULL) {
    return isset($conf['view_mode'])
      ? new ViewModeEntityDisplay($conf['view_mode'])
      : NULL;
  }
}
