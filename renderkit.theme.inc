<?php

/**
 * Theme hook implementation.
 *
 * @param array $variables
 *   Contains the render array at $variables['element'], and the rendered
 *   children's html at $variables['element']['#children'].
 *
 * @return string
 *   Rendered html.
 *
 * @throws Exception
 *
 * @see theme_container()
 */
function theme_renderkit_container($variables) {
  $element = $variables['element'];
  $tagName = isset($element['#tag_name'])
    ? $element['#tag_name']
    : 'div';
  $attributes_str = isset($element['#attributes'])
    ? drupal_attributes($element['#attributes'])
    : '';
  if (!isset($element['#children'])) {
    throw new \Exception('renderkit_container can only be used in #theme_wrappers, not in #theme.');
  }

  return '<' . $tagName . $attributes_str . '>' . $element['#children'] . '</div>';
}

/**
 * @param array $variables
 *
 * @return string
 *   Rendered html.
 *
 * @throws Exception
 * 
 * @see theme_link()
 */
function theme_renderkit_link_wrapper(array $variables) {
  $element = $variables['element'];
  if (!isset($element['#path'])) {
    return '';
  }
  if (empty($element['#children'])) {
    return '';
  }
  $link_options = isset($element['#options'])
    ? $element['#options']
    : array();
  $link_options['html'] = TRUE;
  return l($element['#children'], $element['#path'], $link_options);
}
