<?php

namespace Drupal\cu\Formator;

/**
 * Configurator for associative arrays.
 */
class FormatorD8_AssocTabledrag extends FormatorD8_TabledragBase {

  const ASSOC = TRUE;

  /**
   * @param string $delta
   *
   * @return string|null
   *
   * @see \form_validate_machine_name()
   */
  protected function deltaGetValidationError($delta) {

    if ('' === $delta) {
      return t("Empty machine name '' encountered.");
    }

    // Verify that the machine name contains no disallowed characters.
    if (preg_match('@[^\w]@', $delta, $m)) {
      return t(
        'Machine name @name with invalid character @char encountered.',
        [
          '@name' => var_export($delta, TRUE),
          '@char' => var_export($m[0], TRUE),
        ]);
    }

    return NULL;
  }
}
