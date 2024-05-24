<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;
use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Ock\Ock\Translator\TranslatorInterface;

#[Adapter]
class FormatorD8_Textfield implements FormatorD8Interface {

  /**
   * @param \Ock\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   */
  public function __construct(
    #[Adaptee]
    private readonly Formula_TextfieldInterface $formula,
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

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
      '#description' => $this->formula->getDescription()?->convert($this->translator),
    ];
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public static function validate(array &$element, FormStateInterface $form_state): void {

    /** @var \Ock\Ock\Formula\Textfield\Formula_TextfieldInterface $formula */
    $formula = $element['#formula'];

    foreach ($formula->textGetValidationErrors($element['#value']) as $error) {
      // @todo Make errors translatable.
      $form_state->setError($element, $error);
    }
  }
}
