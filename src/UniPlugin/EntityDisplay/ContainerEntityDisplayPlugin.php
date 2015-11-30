<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay;

use Drupal\entdisp\Manager\EntdispManagerInterface;
use Drupal\renderkit\BuildProcessor\ContainerBuildProcessor;
use Drupal\renderkit\EntityDisplay\Decorator\BuildProcessedEntityDisplay;

use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class ContainerEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var \Drupal\entdisp\Manager\EntdispManagerInterface
   */
  private $entdispManager;

  /**
   * @param string $entityType
   *
   * @return static
   */
  static function create($entityType) {
    return new static(entdisp()->etGetDisplayManager($entityType));
  }

  /**
   * ContainerEntityDisplayPlugin constructor.
   *
   * @param \Drupal\entdisp\Manager\EntdispManagerInterface $entdispManager
   */
  function __construct(EntdispManagerInterface $entdispManager) {
    $this->entdispManager = $entdispManager;
  }

  /**
   * @param array $conf
   *
   * @return array
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
    $form['decorated'] = $this->entdispManager->confGetForm($conf['decorated']);
    $form['decorated']['#title'] = t('Child display plugin');
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
   * @return object|null
   */
  function confGetHandler(array $conf) {
    $conf += array('decorated' => array());
    $decorated = $this->entdispManager->confGetEntityDisplay($conf['decorated']);
    $container = new ContainerBuildProcessor();
    if (!empty($conf['tag_name'])) {
      // @todo Sanitize tag name.
      $container->setTagName($conf['tag_name']);
    }
    if (!empty($conf['classes'])) {
      // @todo Sanitize classes.
      $container->addClasses(explode(' ', $conf['classes']));
    }
    return BuildProcessedEntityDisplay::create($decorated, $container);
  }
}
