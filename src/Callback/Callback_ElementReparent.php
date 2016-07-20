<?php

namespace Drupal\themekit\Callback;

/**
 * Callable object for $element['#process'] to manipulate $element['#parents'].
 *
 * E.g. $element['#process'][] = new Callback_ElementReparent([], 1);
 *
 * See https://www.drupal.org/node/784874
 */
class Callback_ElementReparent {

  /**
   * @var string[]
   */
  private $keysToAppend;

  /**
   * @var int
   */
  private $nKeysToPop;

  /**
   * @param int $nKeysToPop
   *   Number of keys to be removed from $element['#parents']
   * @param string[] $keysToAppend
   *   Keys to append to $element['#parents']
   */
  function __construct($nKeysToPop, array $keysToAppend) {
    $this->keysToAppend = $keysToAppend;
    $this->nKeysToPop = $nKeysToPop;
  }

  /**
   * @param array $element
   *   The original form element.
   *
   * @return array
   *   The modified form element.
   */
  public function __invoke(array $element) {
    $element['#parents'] = array_merge(
      array_slice($element['#parents'], 0, -$this->nKeysToPop),
      $this->keysToAppend);
    return $element;
  }

}
