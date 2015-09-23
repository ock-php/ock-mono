<?php

namespace Drupal\renderkit\Plugin\entdisp;

use Drupal\entdisp\Manager\EntdispPluginManagerInterface;
use Drupal\renderkit\BuildProcessor\ContainerBuildProcessor;
use Drupal\uniplugin\UniPlugin\ConfigurableUniPluginInterface;

class ContainerEntityDisplayPlugin implements ConfigurableUniPluginInterface {

  /**
   * @var \Drupal\entdisp\Manager\EntdispPluginManagerInterface
   */
  private $entdispManager;

  /**
   * @param string $entityType
   *
   * @return static
   */
  static function create($entityType) {
    return new static(entdisp()->etGetManager($entityType));
  }

  /**
   * ContainerEntityDisplayPlugin constructor.
   *
   * @param \Drupal\entdisp\Manager\EntdispPluginManagerInterface $entdispManager
   *   Entity display plugin manager for the container contents entity display.
   */
  function __construct(EntdispPluginManagerInterface $entdispManager) {
    $this->entdispManager = $entdispManager;
  }

  /**
   * @param array $conf
   *
   * @return array
   *
   * @see \views_handler::options_form()
   */
  function settingsForm(array $conf) {
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
    $form['decorated'] = array(
      '#title' => t('Child display plugin'),
      '#type' => UIKIT_ELEMENT_TYPE,
      UIKIT_K_TYPE_OBJECT => $this->entdispManager->getUikitElementType(),
      '#default_value' => isset($conf['decorated']) ? $conf['decorated'] : array(),
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
  function confGetSummary(array $conf, $pluginLabel) {
    return NULL;
  }

  /**
   * @param array $configuration
   *
   * @return object|null
   */
  function confGetHandler(array $configuration = NULL) {
    $configuration += array('decorated' => array());
    $decorated = $this->entdispManager->settingsGetEntityDisplay($configuration['decorated']);
    $container = new ContainerBuildProcessor();
    if (!empty($configuration['tag_name'])) {
      // @todo Sanitize tag name.
      $container->setTagName($configuration['tag_name']);
    }
    if (!empty($configuration['classes'])) {
      // @todo Sanitize classes.
      $container->addClasses(explode(' ', $configuration['classes']));
    }
    return $container->decorate($decorated);
  }
}
