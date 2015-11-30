<?php

namespace Drupal\renderkit\UniPlugin\ListFormat;

use Drupal\renderkit\ListFormat\ItemListFormat;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   id = "html_list",
 *   label = @Translate("HTML list")
 * )
 */
class ItemListLfPlugin extends ConfigurableUniPluginBase {

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
    $form['tag_name'] = array(
      '#type' => 'radios',
      '#title' => t('List type'),
      '#options' => array(
        'ul' => t('Unordered list (ul)'),
        'ol' => t('Ordered list (ol)'),
      ),
      '#default_value' => isset($conf['tag_name']) ? $conf['tag_name'] : 'ul',
    );
    $form['classes'] = array(
      '#title' => t('Classes'),
      '#description' => t('Classes, separated by space'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['classes']) ? $conf['classes'] : '',
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
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface|null
   *   The handler object, or NULL.
   *   Plugins should return handlers of a specific type, but they are not
   *   technically required to do this. This is why an additional check should
   *   be performed for everything returned from a plugin.
   *
   * @throws \Exception
   */
  function confGetHandler(array $conf) {

    $tagName = isset($conf['tag_name']) && 'ol' === $conf['tag_name']
      ? 'ol'
      : 'ul';

    $listFormat = new ItemListFormat($tagName);

    if (!empty($conf['classes'])) {
      $classes = array_unique(array_filter(explode(' ', $conf['classes'])));
      $listFormat->addClasses(explode(' ', $classes));
    }

    return $listFormat;
  }
}
