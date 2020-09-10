<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\Util\UtilBase;

class D7FormUtil extends UtilBase {

  /**
   * Form element #process callback.
   *
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param array $form_state
   * @param array $form
   *
   * @return array
   */
  public static function elementsBuildDependency(array $element, array &$form_state, array $form): array {

    $keys = element_children($element);
    if (\count($keys) < 2) {
      return $element;
    }
    list($dependedKey, $dependingKey) = element_children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!\is_array($dependingElement)) {
      return $element;
    }

    $form_build_id = $form['form_build_id']['#value'];
    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    // See https://www.drupal.org/node/752056 "AJAX Forms in Drupal 7".
    $dependedElement['#ajax'] = [
      /* @see _cfrapi_depended_element_ajax_callback() */
      'callback' => '_cfrapi_depended_element_ajax_callback',
      'wrapper' => $uniqid,
      'method' => 'replace',
    ];

    $dependedElement['#depending_element_reference'] =& $dependingElement;

    // Special handling of ajax for views.
    /* @see views_ui_edit_form() */
    // See https://www.drupal.org/node/1183418
    if (1
      && isset($form_state['view'])
      && $form_state['view'] instanceof \view
      && module_exists('views_ui')
    ) {
      // @todo Does this always work?
      $dependedElement['#ajax']['path'] = empty($form_state['url'])
        ? url($_GET['q'], ['absolute' => TRUE])
        : $form_state['url'];

      # drupal_array_set_nested_value($form_state['values'], $element['#parents'], [], TRUE);
      # drupal_array_set_nested_value($form_state['input'], $element['#parents'], [], TRUE);
    }

    if (empty($dependingElement)) {
      $dependingElement += [
        '#type' => 'themekit_container',
        '#markup' => '<!-- -->',
      ];
    }

    $dependingElement['#prefix'] = '<div id="' . $uniqid . '" class="cfrapi-depending-element-container">';
    $dependingElement['#suffix'] = '</div>';
    $dependingElement['#tree'] = TRUE;

    return $element;
  }

}
