<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Ock\Util\HtmlUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Broken implements FormatorD8Interface {

  /**
   * @param string $message
   */
  public function __construct(private string $message) {
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

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
