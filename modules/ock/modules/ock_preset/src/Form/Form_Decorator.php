<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class Form_Decorator implements FormInterface {

  /**
   * @var \Drupal\Core\Form\FormInterface|null
   */
  private ?FormInterface $decorated;

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId(): string {
    return 'ock_preset_decorator_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param \Drupal\Core\Form\FormInterface|null $decorated
   *
   * @return array
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    FormInterface $decorated = NULL
  ): array {

    if (NULL === $decorated) {
      throw new \RuntimeException("Decorated form object is required.");
    }

    $this->decorated = $decorated;

    return $decorated->buildForm($form, $form_state);
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

    if (NULL === $this->decorated) {
      throw new \RuntimeException("Decorated form object is NULL.");
    }

    $this->decorated->validateForm($form, $form_state);
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    if (NULL === $this->decorated) {
      throw new \RuntimeException("Decorated form object is NULL.");
    }

    $this->decorated->submitForm($form, $form_state);
  }

}
