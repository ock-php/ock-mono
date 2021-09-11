<?php
declare(strict_types=1);

namespace Drupal\cu\Element;

use Donquixote\ObCK\Exception\IncarnatorException;
use Drupal\cu\Formator\FormatorD8;
use Donquixote\ObCK\Formula\Formula;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\cu\DrupalIncarnator;

/**
 * @FormElement("cu")
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
      '#cu_interface' => NULL,
      '#cu_context' => NULL,
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
    $formula = Formula::iface($element['#cu_interface']);

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
