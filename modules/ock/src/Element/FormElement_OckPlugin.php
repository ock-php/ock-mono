<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElementBase;
use Drupal\ock\Formator\FormatorD8;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Ock\Formula\Formula;

/**
 * @FormElement("ock")
 */
class FormElement_OckPlugin extends FormElementBase {

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
      '#ock_interface' => NULL,
      '#ock_context' => NULL,
      '#title' => NULL,
    ];
  }

  /**
   * @param array $element
   *   Original element.
   *
   * @return array
   *   Processed element.
   */
  public static function process(array &$element): array {

    // @todo Filter by context.
    $formula = Formula::iface($element['#ock_interface']);

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
      (string) $element['#title']);

    $element['formula']['#parents'] = $element['#parents'];

    return $element;
  }

  /**
   * {@inheritdoc}
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
