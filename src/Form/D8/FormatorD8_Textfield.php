<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @STA
 */
class FormatorD8_Textfield implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema
   */
  public function __construct(CfSchema_TextfieldInterface $schema) {
    $this->schema = $schema;
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
      '#required' => $this->schema->textIsValid(''),
      '#element_validate' => [[self::class, 'validate']],
      '#schema' => $this->schema,
      '#description' => $this->schema->getDescription(),
    ];
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public static function validate(array &$element, FormStateInterface $form_state) {

    /** @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema */
    $schema = $element['#schema'];

    foreach ($schema->textGetValidationErrors($element['#value']) as $error) {
      // @todo Make errors translatable.
      $form_state->setError($element, $error);
    }
  }
}
