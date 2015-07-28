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

  return '<' . $tagName . $attributes_str . '>' . $element['#children'] . '</' . $tagName . '>';
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

/**
 * @param array $variables
 *
 * @return string
 *   Rendered HTML.
 */
function theme_renderkit_item_list(array $variables) {
  $element = $variables['element'];
  $default_child_attributes = isset($element['#child_attributes'])
    ? $element['#child_attributes'] + array()
    : array();
  $default_child_attributes_str = drupal_attributes($default_child_attributes);

  $html = '';
  foreach (element_children($element) as $key) {
    $child_html = drupal_render($element[$key]);
    if ('' === $child_html) {
      continue;
    }
    if (isset($element[$key]['#attributes'])) {
      $child_attributes = $element[$key]['#attributes'];
      if (isset($child_attributes['class']) && isset($default_child_attributes['class'])) {
        $child_attributes['class'] = array_unique(array_merge($child_attributes['class'], $default_child_attributes['class']));
      }
      $child_attributes += $default_child_attributes;
      $child_attributes_str = drupal_attributes($child_attributes);
    }
    else {
      $child_attributes_str = $default_child_attributes_str;
    }
    $html .= '<li' . $child_attributes_str . '>' . $child_html . '</li>';
  }
  if (isset($element['#tag_name']) && $element['#tag_name'] === 'ol') {
    $tag_name = 'ol';
  }
  else {
    $tag_name = 'ul';
  }

  $attributes_str = isset($element['#attributes'])
    ? drupal_render($element['#attributes'])
    : '';

  return '<' . $tag_name . $attributes_str . '>' . $html . '</' . $tag_name . '>';
}

/**
 * @param array $variables
 *
 * @return string
 */
function theme_renderkit_separator_list(array $variables) {
  $element = $variables['element'];
  $pieces = array();
  foreach (element_children($element) as $key) {
    $pieces[] = drupal_render($element[$key]);
  }
  $separator = isset($element['#separator'])
    ? $element['#separator']
    : '';
  return implode($separator, $pieces);
}
