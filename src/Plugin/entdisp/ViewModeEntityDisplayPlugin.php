<?php

namespace Drupal\renderkit\Plugin\entdisp;

use Drupal\renderkit\EntityDisplay\ViewModeEntityDisplay;
use Drupal\uniplugin\UniPlugin\ConfigurableUniPluginInterface;

class ViewModeEntityDisplayPlugin implements ConfigurableUniPluginInterface {

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
   *
   * @see \entity_views_handler_field_entity::options_form()
   * @see \entity_views_plugin_row_entity_view::options_form()
   */
  function settingsForm(array $conf) {

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
      '#default_value' => $conf['view_mode'],
    );
    return $form;
  }

  /**
   * @param array $conf
   * @param array $form
   * @param array $form_state
   *
   * @see \views_handler::options_validate()
   */
  function settingsFormValidate(array $conf, array &$form, array &$form_state) {
    // Nothing to validate.
  }

  /**
   * @param array $conf
   *
   * @return null|object
   */
  function confGetHandler(array $conf = NULL) {
    return isset($conf['view_mode'])
      ? new ViewModeEntityDisplay($conf['view_mode'])
      : NULL;
  }
}
