<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessor_Wrapper_LinkToEntity;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithBuildProcessor;
use Drupal\renderkit\EntityDisplay\Decorator\EntityDisplay_WithEntityBuildProcessor;
use Drupal\renderkit\EntityDisplay\EntityDisplay_Title;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

class Configurator_EntityDisplay_Title implements ConfiguratorInterface {

  /**
   * @var string[]
   */
  private $allowedTagNames;

  /**
   * @return \Drupal\renderkit\Configurator\Configurator_EntityDisplay_Title
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
   * @param $label
   *
   * @return array
   */
  function confGetForm($conf, $label) {
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
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
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
  function confGetValue($conf) {
    $wrapperTagName = isset($conf['tag_name'])
      ? $conf['tag_name'] : NULL;
    if (!array_key_exists($wrapperTagName, $this->allowedTagNames)) {
      $wrapperTagName = NULL;
    }
    $link = !empty($conf['link']);

    $display = new EntityDisplay_Title();
    if ($link) {
      $wrapper = new EntityBuildProcessor_Wrapper_LinkToEntity();
      $display = new EntityDisplay_WithEntityBuildProcessor($display, $wrapper);
    }
    if ($wrapperTagName) {
      $container = new BuildProcessor_Container();
      $container->setTagName($wrapperTagName);
      $display = new EntityDisplay_WithBuildProcessor($display, $container);
    }
    return $display;
  }
}
