<?php

namespace Drupal\renderkit\UniPlugin\ListFormat;

use Drupal\renderkit\ListFormat\SeparatorListFormat;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   id = "separator",
 *   label = "Separator"
 * )
 */
class SeparatorLfPlugin extends ConfigurableUniPluginBase {

  /**
   * Builds a settings form for the plugin configuration.
   *
   * @param array $conf
   *   Current configuration. Will be empty if not configured yet.
   *
   * @return array
   * @see \views_handler::options_form()
   */
  function confGetForm(array $conf) {
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
   * Gets a handler object that does the business logic.
   *
   * @param array $conf
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
  function confGetValue(array $conf) {
    $separator = isset($conf['separator'])
      ? $conf['separator']
      : '';
    return new SeparatorListFormat($separator);
  }
}
