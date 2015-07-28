<?php

namespace Drupal\renderkit\Helper\Alter;

/**
 * @see hook_field_attach_view_alter()
 */
class FieldAttachViewAlter extends AlterBase {

  const TYPE = 'field_attach_view';

  /**
   * Perform alterations on field_attach_view() or field_view_field().
   *
   * This hook is invoked after the field module has performed the operation.
   *
   * @param array[] $output
   *   Format: $[$fieldName] = $element
   *   The structured content array tree for all of the entity's fields.
   * @param array $context
   *   An associative array containing:
   *   - entity_type: The type of $entity; for example, 'node' or 'user'.
   *   - entity: The entity with fields to render.
   *   - view_mode: View mode; for example, 'full' or 'teaser'.
   *   - display: Either a view mode string or an array of display settings. If
   *     this hook is being invoked from field_attach_view(), the 'display'
   *     element is set to the view mode string. If this hook is being invoked
   *     from field_view_field(), this element is set to the $display argument
   *     and the view_mode element is set to '_custom'. See field_view_field()
   *     for more information on what its $display argument contains.
   *   - language: The language code used for rendering.
   */
  function alter(array &$output, array &$context) {
    foreach ($this->getFunctions() as $function) {
      $function($output, $context, NULL, NULL);
    }
  }

}
