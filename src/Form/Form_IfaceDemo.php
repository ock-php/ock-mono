<?php
declare(strict_types=1);

namespace Drupal\ock\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class Form_IfaceDemo implements FormInterface {

  public const KEY = 'plugin';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ock_iface_demo_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $interface = NULL): array {

    if (NULL === $interface) {
      throw new \RuntimeException("Interface demo form requires an interface as argument.");
    }

    $settings = $_GET[self::KEY] ?? [];

    $form[self::KEY] = [
      /* @see \Drupal\ock\Element\FormElement_CuPlugin */
      '#type' => 'ock',
      '#ock_interface' => $interface,
      '#title' => t('Plugin'),
      '#default_value' => $settings,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Show'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    $form_state->setRedirect(
      '<current>',
      [self::KEY => $form_state->getValue(self::KEY)]);
  }
}
