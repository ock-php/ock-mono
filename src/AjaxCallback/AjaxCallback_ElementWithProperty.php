<?php

declare(strict_types=1);

namespace Drupal\ock\AjaxCallback;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajax callback that returns a form element with a matching property value.
 */
class AjaxCallback_ElementWithProperty implements AjaxCallbackInterface {

  /**
   * @var string
   */
  private string $property;

  /**
   * @var mixed
   */
  private $value;

  /**
   * Constructor.
   *
   * @param string $property
   *   Property name.
   * @param mixed $value
   *   Expected property value.
   */
  public function __construct(string $property, $value) {
    $this->property = $property;
    $this->value = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function __invoke(array $form, FormStateInterface $form_state, Request $request) {
    $candidate = $this->findNestedElement($form);
    if ($candidate === NULL) {
      throw new \Exception('Element not found.');
    }
    return $candidate;
  }

  /**
   * Finds an element in a nested elements tree.
   *
   * @param array $element
   *   Parent render or form element.
   *
   * @return array|null
   *   The element with the expected property value, or NULL if not found.
   */
  private function findNestedElement(array $element): ?array {
    // Check the element itself.
    if (($element[$this->property] ?? NULL) === $this->value) {
      return $element;
    }
    // Search children in the subtree, recursively.
    foreach (Element::children($element) as $name) {
      $candidate = self::findNestedElement($element[$name]);
      if ($candidate !== NULL) {
        return $candidate;
      }
    }
    // Not found in subtree.
    return NULL;
  }

}
