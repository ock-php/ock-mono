<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\Element\FormElement_Formula;
use Ock\Ock\Core\Formula\FormulaInterface;

class Form_FormulaDemo implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ock_formula_demo_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Ock\Ock\Core\Formula\FormulaInterface|null $formula
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
      '#type' => FormElement_Formula::ID,
      '#cf_formula' => $formula,
      '#title' => \t('Plugin'),
      '#default_value' => $conf,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => \t('Show'),
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
