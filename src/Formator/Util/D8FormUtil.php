<?php
declare(strict_types=1);

namespace Drupal\cu\Formator\Util;

use Donquixote\ObCK\Util\UtilBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class D8FormUtil extends UtilBase {

  /**
   * Form element #process callback.
   *
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $form
   *
   * @return array
   */
  public static function elementsBuildDependency(
    array $element,
    FormStateInterface $form_state,
    array $form
  ): array {

    $keys = Element::children($element);
    if (\count($keys) < 2) {
      return $element;
    }
    list($dependedKey, $dependingKey) = Element::children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!\is_array($dependingElement)) {
      return $element;
    }

    # $form_build_id = $form['form_build_id']['#value'];
    $uniqid_raw = ''
      . implode( '--', $element['#parents'])
      # . '--' . $form_build_id
      . '--' . $form_state->getFormObject()->getFormId()
      . '';

    // Replace special characters which could confuse CSS selectors.
    $uniqid = preg_replace('@[^a-zA-Z0-9_\-]@', '-', $uniqid_raw);

    // See https://www.drupal.org/node/752056 "AJAX Forms in Drupal 7".
    $dependedElement['#ajax'] = [
      /* @see dependedElementAjaxCallback() */
      'callback' => [self::class, 'dependedElementAjaxCallback'],
      'wrapper' => $uniqid,
      'method' => 'replace',
    ];

    $dependedElement['#depending_element_reference'] =& $dependingElement;

    // Special handling of ajax for views.
    /* @see views_ui_edit_form() */
    // See https://www.drupal.org/node/1183418
    /*
    if (1
      && isset($form_state['view'])
      && module_exists('views_ui')
      && $form_state['view'] instanceof \view
    ) {
      // @todo Does this always work?
      $dependedElement['#ajax']['path'] = empty($form_state['url'])
        ? url($_GET['q'], array('absolute' => TRUE))
        : $form_state['url'];

      # drupal_array_set_nested_value($form_state['values'], $element['#parents'], [], TRUE);
      # drupal_array_set_nested_value($form_state['input'], $element['#parents'], [], TRUE);
    }
    */

    if (empty($dependingElement)) {
      $dependingElement += [
        '#type' => 'themekit_container',
        '#markup' => '<!-- -->',
      ];
    }

    $dependingElement['#prefix'] = '<div id="' . $uniqid . '" class="cu-depending-element-container">';
    $dependingElement['#suffix'] = '</div>';
    $dependingElement['#tree'] = TRUE;

    return $element;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *
   * @return mixed
   */
  public static function dependedElementAjaxCallback(
    /** @noinspection PhpUnusedParameterInspection */ array $form,
    FormStateInterface $formState
  ) {

    return $formState->getTriggeringElement()['#depending_element_reference'];
  }

}
