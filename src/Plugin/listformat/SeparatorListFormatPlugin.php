<?php

namespace Drupal\renderkit\Plugin\ListFormat;

use Drupal\renderkit\ListFormat\SeparatorListFormat;
use Drupal\uniplugin\UniPlugin\ConfigurableUniPluginInterface;

class SeparatorListFormatPlugin implements ConfigurableUniPluginInterface {

  /**
   * Builds a settings form for the plugin configuration.
   *
   * @param array $conf
   *   Current configuration. Will be empty if not configured yet.
   *
   * @return array
   *
   * @see \views_handler::options_form()
   */
  function settingsForm(array $conf) {
    $form = array();
    $form['separator'] = array(
      '#title' => t('Separator'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['separator'])
        ? $conf['separator']
        : '',
    );
    return $form;
  }

  /**
   * Validation callback for the settings form.
   *
   * @param array $conf
   * @param array $form
   * @param array $form_state
   *
   * @see \views_handler::options_validate()
   */
  function settingsFormValidate(array $conf, array &$form, array &$form_state) {
    // @todo XSS validation?
  }

  /**
   * @param array $conf
   *   Plugin configuration.
   * @param string $pluginLabel
   *   Label from the plugin definition.
   *
   * @return string|null
   */
  function confGetSummary(array $conf, $pluginLabel) {
    return NULL;
  }

  /**
   * Gets a handler object that does the business logic.
   *
   * @param array $configuration
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   *   The handler object, or NULL.
   *   Plugins should return handlers of a specific type, but they are not
   *   technically required to do this. This is why an additional check should
   *   be performed for everything returned from a plugin.
   *
   * @throws \Exception
   */
  function confGetHandler(array $configuration = NULL) {
    $separator = isset($configuration['separator'])
      ? $configuration['separator']
      : '';
    return new SeparatorListFormat($separator);
  }
}
