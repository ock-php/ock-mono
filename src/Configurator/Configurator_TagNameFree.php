<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\Optionable\Configurator_TextfieldBase;

/** @noinspection PhpDeprecationInspection */
class Configurator_TagNameFree extends Configurator_TextfieldBase {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    $form = parent::confGetForm($conf, $label);
    $form['#element_validate'][] = [$this, 'elementValidate'];
    return $form;
  }

  /**
   * Form #element_validate callback.
   *
   * @param array $element
   * @param array $form_state
   * @param array $form
   */
  public function elementValidate(
    array $element,
    /** @noinspection PhpUnusedParameterInspection */ array $form_state,
    /** @noinspection PhpUnusedParameterInspection */ array $form
  ) {
    if (!preg_match('/^[\w]+$/', $element['#value'])) {
      form_error($element, t('Value does not seem to be a valid HTML tag name.'));
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    if (!is_string($conf)) {
      return NULL;
    }
    // @todo Smarter validation rules?
    if (!preg_match('/^[\w]+$/', $conf)) {
      return NULL;
    }
    return $conf;
  }
}
