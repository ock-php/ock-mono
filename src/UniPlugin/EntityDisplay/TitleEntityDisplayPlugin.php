<?php

namespace Drupal\renderkit\UniPlugin\EntityDisplay;

use Drupal\renderkit\BuildProcessor\ContainerBuildProcessor;
use Drupal\renderkit\EntityBuildProcessor\Wrapper\EntityLinkWrapper;
use Drupal\renderkit\EntityDisplay\Decorator\BuildProcessedEntityDisplay;
use Drupal\renderkit\EntityDisplay\Decorator\ProcessedEntityDisplay;
use Drupal\renderkit\EntityDisplay\TitleEntityDisplay;
use Drupal\uniplugin\UniPlugin\Configurable\ConfigurableUniPluginBase;

class TitleEntityDisplayPlugin extends ConfigurableUniPluginBase {

  /**
   * @var string[]
   */
  private $allowedTagNames;

  /**
   * @return \Drupal\renderkit\UniPlugin\EntityDisplay\TitleEntityDisplayPlugin
   */
  static function create() {
    return new self(array('h1', 'h2', 'h3', 'h4', 'strong'));
  }

  /**
   * TitleEntityDisplayPlugin constructor.
   *
   * @param string[] $allowedTagNames
   */
  function __construct(array $allowedTagNames) {
    $this->allowedTagNames = array_combine($allowedTagNames, $allowedTagNames);
  }

  /**
   * @param array $conf
   *
   * @return array
   */
  function confGetForm(array $conf) {
    $tagName = isset($conf['tag_name'])
      ? $conf['tag_name'] : NULL;
    $link = !empty($conf['link']);
    $form = array();
    $form['tag_name'] = array(
      '#type' => 'select',
      '#options' => $this->allowedTagNames,
      '#title' => t('Wrapper'),
      '#empty_option' => t('- No wrapper -'),
      '#default_value' => $tagName,
    );
    $form['link'] = array(
      '#type' => 'checkbox',
      '#title' => t('Link to entity'),
      '#default_value' => $link,
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
    $wrapperTagName = isset($conf['tag_name'])
      ? $conf['tag_name'] : NULL;
    if (!array_key_exists($wrapperTagName, $this->allowedTagNames)) {
      $wrapperTagName = NULL;
    }
    $link = !empty($conf['link']);
    if ($link && $wrapperTagName) {
      return $wrapperTagName . ', ' . t('linked to entity') . '.';
    }
    elseif ($link) {
      return t('Linked to entity');
    }
    elseif ($wrapperTagName) {
      return $wrapperTagName;
    }
    else {
      return t('Raw title');
    }
  }

  /**
   * Gets a handler object that does the business logic, or null, or dummy
   * object.
   *
   * @param array $conf
   *   Configuration for the handler object creation, if this plugin is
   *   configurable.
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  function confGetValue(array $conf) {
    $wrapperTagName = isset($conf['tag_name'])
      ? $conf['tag_name'] : NULL;
    if (!array_key_exists($wrapperTagName, $this->allowedTagNames)) {
      $wrapperTagName = NULL;
    }
    $link = !empty($conf['link']);

    $display = new TitleEntityDisplay();
    if ($link) {
      $wrapper = new EntityLinkWrapper();
      $display = new ProcessedEntityDisplay($display, $wrapper);
    }
    if ($wrapperTagName) {
      $container = new ContainerBuildProcessor();
      $container->setTagName($wrapperTagName);
      $display = new BuildProcessedEntityDisplay($display, $container);
    }
    return $display;
  }
}
