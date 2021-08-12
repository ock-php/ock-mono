<?php
declare(strict_types=1);

namespace Drupal\cu\Form;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class Form_FormulaDemo implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cu_formula_demo_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface|null $formula
   *   Formula.
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state, FormulaInterface $formula = NULL): array {

    if (NULL === $formula) {
      throw new \RuntimeException("Formula demo form requires a factory formula as argument.");
    }

    $conf = $_GET['conf'] ?? [];

    $form['conf'] = [
      /* @see \Drupal\cu\Element\FormElement_CfFormula */
      '#type' => 'cu_cf_formula',
      '#cf_formula' => $formula,
      '#title' => t('Plugin'),
      '#default_value' => $conf,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Show'),
    ];

    return $form;
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Elements do their own validation.
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $form_state->setRedirect(
      '<current>',
      ['conf' => $form_state->getValue('conf')]);
  }
}
