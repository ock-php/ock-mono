<?php

namespace Drupal\renderkit\Configurator;


use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessor_Container;

/**
 * @_Cfr_Plugin_(
 *   id = "container",
 *   label = @t("Container")
 * )
 *
 * @deprecated
 */
class Configurator_BuildProcessor_Container implements ConfiguratorInterface {

  /**
   * @param array $conf
   *
   * @param $label
   *
   * @return array
   * @see \views_handler::options_form()
   */
  public function confGetForm($conf, $label) {
    $form['tag_name'] = [
      '#title' => t('Tag name'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['tag_name']) ? $conf['tag_name'] : 'div',
      // @todo Add validation for tag name.
    ];
    $form['classes'] = [
      '#title' => t('Classes'),
      '#description' => t('Classes, separated by space'),
      '#type' => 'textfield',
      '#default_value' => isset($conf['classes']) ? $conf['classes'] : '',
      // @todo Add validation for classes.
    ];
    return $form;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return NULL;
  }

  /**
   * @param array $conf
   *
   * @return \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  public function confGetValue($conf) {
    // @todo What is the purpose of this 'decorated' key?
    $conf += ['decorated' => []];
    $container = new BuildProcessor_Container();
    if (!empty($conf['tag_name'])) {
      // @todo Sanitize tag name.
      $container->setTagName($conf['tag_name']);
    }
    if (!empty($conf['classes'])) {
      // @todo Sanitize classes.
      $container->addClasses(explode(' ', $conf['classes']));
    }
    return $container;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    // @todo What is the purpose of this 'decorated' key?
    $conf += array('decorated' => array());
    $php = 'new ' . BuildProcessor_Container::class . '()';
    $php_suffix = '';
    if (!empty($conf['tag_name'])) {
      // @todo Sanitize tag name.
      $php_suffix .= '->setTagName(' . var_export($conf['tag_name'], TRUE) . ')';
    }
    if (!empty($conf['classes'])) {
      // @todo Sanitize classes.
      $php_suffix .= '->addClasses(' . var_export(explode(' ', $conf['classes']), TRUE) . ')';
    }
    if ('' !== $php_suffix) {
      $php = '(' . $php . ')' . $php_suffix;
    }
    return $php;
  }
}
