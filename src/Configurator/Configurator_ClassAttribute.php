<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_ClassAttribute implements ConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function create() {
    return new self();
  }

  /**
   * @param array $element
   * @param array $form_state
   */
  public static function elementValidate(
    array $element,
    /** @noinspection PhpUnusedParameterInspection */ array &$form_state
  ) {
    $value = $element['#value'];
    if ('' === $value) {
      return;
    }
    $classes = array();
    foreach (explode(' ', $value) as $class) {
      $replacements = array(
        '%name' => $element['#title'],
        '!class' => '<code>' . check_plain($class) . '</code>',
      );
      if ('' === $class) {
        form_error($element, t('%name contains more white space than needed.', $replacements));
      }
      elseif (array_key_exists($class, $classes)) {
        form_error($element, t('%name contains a duplicate class name: !class.', $replacements));
      }
      elseif (preg_match('[^A-Za-z0-9_-]', $class)) {
        form_error($element, t('%name: Class !class contains characters that are not allowed in class names.', $replacements));
      }
      $classes[$class] = $class;
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return array(
      '#title' => $label,
      '#description' => t('Classes, separated by space'),
      '#type' => 'textfield',
      '#default_value' => is_string($conf) ? $conf : '',
      '#element_validate' => array(array(__CLASS__, 'elementValidate')),
    );
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return NULL !== $conf && '' !== $conf && is_string($conf)
      ? '<code>' . check_plain($conf) . '</code>'
      : NULL;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string[]
   *   Array of class names.
   *   May contain duplicate names, and empty names. This is a bit ugly, but it
   *   still results in valid HTML, so we don't care.
   */
  public function confGetValue($conf) {
    if ('' === $conf || !is_string($conf)) {
      return array();
    }
    // Keep only those class names that don't contain invalid characters, and that are not empty.
    $classes = array();
    foreach (explode(' ', $conf) as $class) {
      if ('' !== $class) {
        if (!preg_match('[^A-Za-z0-9_-]', $class)) {
          $classes[] = $class;
        }
      }
    }
    return $classes;
  }
}
