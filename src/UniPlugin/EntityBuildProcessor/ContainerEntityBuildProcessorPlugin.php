<?php

namespace Drupal\renderkit\UniPlugin\EntityBuildProcessor;


use Drupal\renderkit\BuildProcessor\ContainerBuildProcessor;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

/**
 * @UniPlugin(
 *   id = "container"
 *   label = @Translate("Container")
 * )
 */
class ContainerEntityBuildProcessorPlugin extends ConfigurableUniPluginBase {

  /**
   * @param array $conf
   *
   * @return array
   *
   * @see \views_handler::options_form()
   */
  function confGetForm(array $conf) {
    $form['tag_name'] = array(
      '#title' => t('Tag name'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['tag_name']) ? $conf['tag_name'] : 'div',
      // @todo Add validation for tag name.
    );
    $form['classes'] = array(
      '#title' => t('Classes'),
      '#description' => t('Classes, separated by space'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['classes']) ? $conf['classes'] : '',
      // @todo Add validation for classes.
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
   * @param array $configuration
   *
   * @return \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  function confGetHandler(array $configuration = NULL) {
    $configuration += array('decorated' => array());
    $container = new ContainerBuildProcessor();
    if (!empty($configuration['tag_name'])) {
      // @todo Sanitize tag name.
      $container->setTagName($configuration['tag_name']);
    }
    if (!empty($configuration['classes'])) {
      // @todo Sanitize classes.
      $container->addClasses(explode(' ', $configuration['classes']));
    }
    return $container;
  }

}
