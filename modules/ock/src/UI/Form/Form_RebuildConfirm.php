<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Form;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\ock\UI\Controller\Controller_ReportOverview;

class Form_RebuildConfirm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ock_rebuild_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion(): string {
    return $this->t('Rediscover ock plugins?')->render();
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

    // @todo Clear the ock cache.

    \Drupal::messenger()->addMessage(
      $this->t('The ock plugin cache has been cleared.'));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
