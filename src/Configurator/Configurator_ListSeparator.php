<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_ListSeparator implements ConfiguratorInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return array(
      '#title' => t('Separator'),
      /* @see theme_textfield() */
      '#type' => 'textfield',
      '#maxlength' => 20,
      '#size' => 6,
      '#default_value' => is_string($conf) ? $conf : NULL,
    );
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return is_string($conf)
      ? check_plain(json_encode(substr($conf, 0, 7)))
      : NULL;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    if (!is_string($conf)) {
      $conf = '';
    }
    return check_plain($conf);
  }
}
