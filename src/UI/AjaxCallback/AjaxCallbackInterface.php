<?php

declare(strict_types=1);

namespace Drupal\ock\UI\AjaxCallback;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Callback for $['#ajax']['callback'].
 *
 * @see \Drupal\Core\Form\FormAjaxResponseBuilder::buildResponse()
 */
interface AjaxCallbackInterface {

  /**
   * Executes the callback to get a response.
   *
   * @param array $form
   *   Complete form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request.
   *
   * @return array|\Drupal\Core\Ajax\AjaxResponse
   *   Render element or ajax response object.
   *
   * @throws \Exception
   *   Cannot build response.
   */
  public function __invoke(array $form, FormStateInterface $form_state, Request $request): AjaxResponse|array;

}
