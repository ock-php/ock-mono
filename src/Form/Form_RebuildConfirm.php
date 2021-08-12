<?php
declare(strict_types=1);

namespace Drupal\cu\Form;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\cu\Controller\Controller_ReportOverview;

class Form_RebuildConfirm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cu_rebuild_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion(): string {
    return $this->t('Rediscover cu plugins?')->render();
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url {
    return Controller_ReportOverview::route()->url();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText(): MarkupInterface {
    return $this->t('Rebuild');
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

    // @todo Clear the cu cache.

    \Drupal::messenger()->addMessage(
      $this->t('The cu plugin cache has been cleared.'));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
