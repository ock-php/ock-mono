<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Donquixote\Ock\Exception\IncarnatorException;
use Drupal\ock\Formator\FormatorD8;
use Donquixote\Ock\Formula\Formula;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\ock\DrupalIncarnator;

/**
 * @FormElement("ock")
 */
class FormElement_CuPlugin extends FormElement {

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
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $complete_form
   *
   * @return array
   *   Processed element.
   */
  public static function process(
    array &$element,
    /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state,
    /** @noinspection PhpUnusedParameterInspection */ array &$complete_form
  ): array {

    // @todo Filter by context.
    $formula = Formula::iface($element['#ock_interface']);

    try {
      $formator = FormatorD8::fromFormula(
        $formula,
        DrupalIncarnator::fromContainer());
    }
    catch (IncarnatorException $e) {
      $element['#markup'] = \t('Unsupported formula: @message', [
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
