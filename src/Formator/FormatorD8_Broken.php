<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Util\HtmlUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Broken implements FormatorD8Interface {

  /**
   * @var string
   */
  private $message;

  /**
   * @param string $message
   */
  public function __construct($message) {
    $this->message = $message;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    $form = [];

    $form['content'] = [
      '#type' => 'container',
    ];

    $form['content']['messages'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['error', 'messages']],
    ];

    $form['content']['messages']['message'] = [
      '#markup' => HtmlUtil::sanitize($this->message),
    ];

    $form['#element_validate'][] = function(array $element, FormStateInterface $form_state) {
      $form_state->setError(
        $element,
        "Unsupported formula. The form will always fail to validate.");
    };

    return $form;
  }
}
