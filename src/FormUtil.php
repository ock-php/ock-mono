<?php

namespace Drupal\renderkit;

/**
 * @final
 */
abstract class FormUtil {

  /**
   * @param array $element
   */
  static function onProcessBuildDependency(array &$element) {
    /* @see _renderkit_process_element_dependency() */
    $element['#process'][] = '_renderkit_process_element_dependency';
  }

  /**
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param array $form
   * @param array $form_state
   *
   * @return array
   */
  static function elementsBuildDependency(array &$element, array $form, array $form_state) {

    list($dependedKey, $dependingKey) = element_children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!isset($form['form_build_id'])) {
      dpm(ddebug_backtrace(TRUE));
    }

    if (!isset($element['#name'])) {
      # dpm(ddebug_backtrace(TRUE));
    }

    $form_build_id = $form['form_build_id']['#value'];
    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    // See https://www.drupal.org/node/752056 "AJAX Forms in Drupal 7".
    $dependedElement['#ajax'] = array(
      /* @see _renderkit_depended_element_ajax_callback() */
      'callback' => '_renderkit_depended_element_ajax_callback',
      'wrapper' => $uniqid,
      'method' => 'replace',
    );

    $dependedElement['#depending_element_reference'] =& $dependingElement;

    // Special handling of ajax for views.
    /* @see views_ui_edit_form() */
    // See https://www.drupal.org/node/1183418
    if (1
      && isset($form_state['view'])
      && module_exists('views_ui')
      && $form_state['view'] instanceof \view
    ) {
      // @todo Does this always work?
      $dependedElement['#ajax']['path'] = $_GET['q'];
    }

    if (empty($dependingElement)) {
      $dependingElement += array(
        '#type' => 'renderkit_container',
        '#markup' => '<!-- -->',
      );
    }

    $dependingElement['#prefix'] = '<div id="' . $uniqid . '">';
    $dependingElement['#suffix'] = '</div>';
    $dependingElement['#tree'] = TRUE;
  }

}
