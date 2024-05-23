<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;

#[Adapter]
class FormatorD8_Textfield implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
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
  public static function validate(array &$element, FormStateInterface $form_state) {

    /** @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula */
    $formula = $element['#formula'];

    foreach ($formula->textGetValidationErrors($element['#value']) as $error) {
      // @todo Make errors translatable.
      $form_state->setError($element, $error);
    }
  }
}
