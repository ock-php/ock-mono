<?php
declare(strict_types=1);

namespace Drupal\cu\Element;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Drupal\cu\Formator\FormatorD8;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\cu\FormulaToAnything;

/**
 * @FormElement("cu_cf_formula")
 */
class FormElement_Formula extends FormElement {

  public const ID = 'cu_cf_formula';

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
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $complete_form
   *
   * @return array
   */
  public static function process(
    array &$element,
    /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state,
    /** @noinspection PhpUnusedParameterInspection */ array &$complete_form
  ): array {

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
      $formator = FormatorD8::fromFormula(
        $formula,
        FormulaToAnything::fromContainer());
    }
    catch (FormulaToAnythingException $e) {
      $element['#markup'] = \t('Unsupported formula: @message', [
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
    $input,
    FormStateInterface $form_state
  ) {
    if ($input !== FALSE) {
      return $input;
    }

    /** @var mixed $value */
    $value = $element['#default_value'] ?? [];
    return $value;
  }
}
