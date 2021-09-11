<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @STA
 */
class FormatorD8_Textfield implements FormatorD8Interface {

  /**
   * @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface
   */
  private $formula;

  /**
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   */
  public function __construct(Formula_TextfieldInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_string($conf)) {
      $conf = '';
    }

    return [
      /* @see \Drupal\Core\Render\Element\Textfield */
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $conf,
      '#required' => $this->formula->textIsValid(''),
      '#element_validate' => [[self::class, 'validate']],
      '#formula' => $this->formula,
      '#description' => $this->formula->getDescription(),
    ];
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public static function validate(array &$element, FormStateInterface $form_state) {

    /** @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula */
    $formula = $element['#formula'];

    foreach ($formula->textGetValidationErrors($element['#value']) as $error) {
      // @todo Make errors translatable.
      $form_state->setError($element, $error);
    }
  }
}
