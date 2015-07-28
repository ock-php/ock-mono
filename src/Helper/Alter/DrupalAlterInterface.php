<?php
namespace Drupal\renderkit\Helper\Alter;

/**
 * @see drupal_alter()
 */
interface DrupalAlterInterface {

  /**
   * Finds implementations for drupal_alter().
   *
   * @param string|string[] $type
   *   A string describing the type of the alterable $data. 'form', 'links',
   *   'node_content', and so on are several examples. Alternatively can be an
   *   array, in which case hook_TYPE_alter() is invoked for each value in the
   *   array, ordered first by module, and then for each module, in the order of
   *   values in $type. For example, when Form API is using drupal_alter() to
   *   execute both hook_form_alter() and hook_form_FORM_ID_alter()
   *   implementations, it passes array('form', 'form_' . $form_id) for $type.
   *
   * @return string[]
   *   Functions to be called.
   */
  function getFunctions($type);
}
