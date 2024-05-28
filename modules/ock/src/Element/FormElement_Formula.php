<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Attribute\FormElement;
use Drupal\Core\Render\Element\FormElementBase;
use Drupal\ock\Formator\FormatorD8;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Ock\Core\Formula\FormulaInterface;

#[FormElement(self::ID)]
class FormElement_Formula extends FormElementBase {

  public const ID = 'ock_cf_formula';

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return [
      '#input' => TRUE,
      '#tree' => TRUE,
      '#process' => [
        /* @see process() */
        [self::class, 'process'],
      ],
      // This needs to be set.
      '#cf_formula' => NULL,
      '#title' => NULL,
    ];
  }

  /**
   * @param array $element
   *
   * @return array
   */
  public static function process(array &$element): array {

    if (!isset($element['#cf_formula'])) {
      $element['line']['#markup'] = '<div>Line ' . __LINE__ . '</div>';
      return $element;
    }

    $formula = $element['#cf_formula'];

    if (!$formula instanceof FormulaInterface) {
      $element['line']['#markup'] = '<div>Line ' . __LINE__ . '</div>';
      return $element;
    }

    try {
      $formator = FormatorD8::fromFormula($formula);
    }
    catch (AdapterException $e) {
      $element['message']['#markup'] = \t('Unsupported formula: @message', [
        '@message' => $e->getMessage(),
      ]);
      return $element;
    }

    $element['formula'] = $formator->confGetD8Form(
      $element['#value'],
      $element['#title']);

    $element['formula']['#parents'] = $element['#parents'];

    return $element;
  }

  /**
   * @param array $element
   * @param mixed $input
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public static function valueCallback(
    &$element,
    mixed $input,
    FormStateInterface $form_state
  ): mixed {
    if ($input !== FALSE) {
      return $input;
    }

    /** @var mixed $value */
    $value = $element['#default_value'] ?? [];
    return $value;
  }
}
