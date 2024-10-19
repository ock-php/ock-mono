<?php
declare(strict_types=1);

namespace Drupal\ock\Element;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Attribute\FormElement;
use Drupal\Core\Render\Element\FormElementBase;
use Drupal\ock\Formator\FormatorD8;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Ock\Formula\Formula;

#[FormElement(self::ELEMENT_TYPE)]
class FormElement_OckPlugin extends FormElementBase {

  const ELEMENT_TYPE = 'ock';

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
   * Creates a form element of this type.
   *
   * This method exists so that developers don't need to remember the property
   * names available for this element type.
   *
   * @param string $interface
   *   Interface.
   * @param string|\Drupal\Component\Render\MarkupInterface $title
   *   Title.
   * @param array|null $default_value
   *   Default value.
   * @param mixed $context
   *   (optional) Context.
   *
   * @return array
   *   Form element array.
   */
  public static function createElement(
    string $interface,
    string|MarkupInterface $title,
    array|null $default_value = NULL,
    mixed $context = NULL,
  ): array {
    $element = [
      '#type' => self::ELEMENT_TYPE,
      '#ock_interface' => $interface,
      '#title' => $title,
      '#default_value' => $default_value,
    ];
    if ($context !== NULL) {
      $element['#ock_context'] = $context;
    }
    return $element;
  }

  /**
   * @param array $element
   *   Original element.
   *
   * @return array
   *   Processed element.
   */
  public static function process(array &$element): array {
    $interface = $element['#ock_interface']
      ?? throw new \RuntimeException('Missing key #ock_interface in form element.');

    // @todo Filter by context.
    $formula = Formula::iface($interface);

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
