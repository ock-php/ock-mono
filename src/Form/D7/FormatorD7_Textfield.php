<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;

/**
 * @STA
 */
class FormatorD7_Textfield implements FormatorD7Interface {

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
  public function confGetD7Form($conf, ?string $label): array {

    if (!\is_string($conf)) {
      $conf = '';
    }

    return [
      /* @see \Drupal\Core\Render\Element\Textfield */
      '#type' => 'textfield',
      '#title' => $label,
      '#default_value' => $conf,
      '#required' => $this->schema->textIsValid(''),
      '#element_validate' => [self::class, 'validate'],
      '#schema' => $this->schema,
    ];
  }

  /**
   * @param array $element
   * @param array &$form_state
   */
  public static function validate(array &$element,
    /** @noinspection PhpUnusedParameterInspection */ array &$form_state
  ) {

    /** @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema */
    $schema = $element['#schema'];

    foreach ($schema->textGetValidationErrors($element['#value']) as $error) {
      // @todo Make errors translatable.
      form_set_error($element, $error);
    }
  }
}
