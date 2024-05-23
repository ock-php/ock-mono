<?php
declare(strict_types=1);

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Markup;
use Drupal\themekit\Util\HtmlUtil;
use Drupal\themekit\Util\ListUtil;

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
 * @see template_preprocess_container()
 */
function theme_themekit_container(array $variables) {

  $element = $variables['element'];

  if (!isset($element['#children']) || '' === $element['#children']) {
    return '';
  }

  $tagName = $element['#tag_name'] ?? 'div';

  $attributes_str = HtmlUtil::elementAttributesString($element);

  return '<' . $tagName . $attributes_str . '>'
    . $element['#children']
    . '</' . $tagName . '>'
    . "\n";
}

/**
 * @param array $variables
 *
 * @return \Drupal\Component\Render\MarkupInterface|string
 *
 * @throws \Exception
 */
function theme_themekit_link_wrapper(array $variables) {
  $element = $variables['element'];

  if (!isset($element['#children']) || '' === $element['#children']) {
    # dpm(ddebug_backtrace(TRUE), __FUNCTION__);
    return '??';
  }

  $link = [
    '#type' => 'link',
  ];

  foreach (['#url', '#options', '#attributes'] as $k) {
    if (isset($element[$k])) {
      $link[$k] = $element[$k];
    }
  }

  $link['#title'] = $element['#children'];

  /** @var \Drupal\Core\Render\RendererInterface $renderer */
  $renderer = \Drupal::service('renderer');

  return $renderer->render($link);
}

/**
 * @param array $variables
 *
 * @return string
 */
function theme_themekit_item_containers(array $variables) {

  $element = $variables['element'];

  try {
    $items_rendered = ListUtil::elementRenderListItems(
      $element,
      \Drupal::service('renderer'));
  }
  catch (Exception $e) {
    // @todo Log this!
    unset($e);
    return '<!-- Something went wrong here. -->';
  }

  if ([] === $items_rendered) {
    return '';
  }

  if (!isset($element['#item_tag_name'])) {
    $tag_name = 'div';
    $item_close_tag = '</div>';
  }
  elseif (false !== $element['#item_tag_name']) {
    $tag_name = $element['#item_tag_name'];
    $item_close_tag = '</' . $tag_name . '>';
  }
  else {
    return implode('', $items_rendered);
  }

  $item_attributes_str = HtmlUtil::elementAttributesString(
    $element,
    '#item_attributes');

  $item_open_tag = '<' . $tag_name . $item_attributes_str . '>';

  $html = '';
  foreach ($items_rendered as $delta => $item_rendered) {
    $html .= $item_open_tag
      . $item_rendered
      . $item_close_tag;
  }

  return $html;
}

/**
 * @param array $variables
 *
 * @return string
 *   Rendered HTML.
 */
function theme_themekit_list(array $variables) {
  $element = $variables['element'];

  try {
    $items_rendered = ListUtil::elementRenderListItems(
      $element,
      \Drupal::service('renderer'));
  }
  catch (Exception $e) {
    // @todo Log this!
    unset($e);
    return '<!-- Something went wrong here. -->';
  }

  if ([] === $items_rendered) {
    return '';
  }

  $default_child_attributes = isset($element['#child_attributes'])
    ? $element['#child_attributes'] + []
    : [];

  $default_child_attributes_str = HtmlUtil::attributes(
    $default_child_attributes);

  $html = '';
  foreach ($items_rendered as $key => $child_html) {

    if (isset($element[$key]['#attributes'])) {
      $child_attributes = $element[$key]['#attributes'];
      if (isset($child_attributes['class'], $default_child_attributes['class'])) {
        $child_attributes['class'] = array_unique(array_merge($child_attributes['class'], $default_child_attributes['class']));
      }
      $child_attributes += $default_child_attributes;
      $child_attributes_str = HtmlUtil::attributes($child_attributes);
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

  $attributes_str = HtmlUtil::elementAttributesString($element);

  return ''
    . '<' . $tag_name . $attributes_str . '>'
    . $html
    . '</' . $tag_name . '>'
    . "\n";
}

/**
 * @param array $variables
 *
 * @return string
 */
function theme_themekit_separator_list(array $variables) {
  $element = $variables['element'];

  try {
    $pieces = ListUtil::elementRenderListItems(
      $element,
      \Drupal::service('renderer'));
  }
  catch (Exception $e) {
    unset($e);
    return '<!-- Something went wrong here. -->';
  }

  if (!isset($element['#separator'])) {
    $separator_safe = '';
  }
  else {
    $separator = $element['#separator'];
    if (is_string($separator)) {
      $separator_safe = Html::escape($separator);
    }
    elseif ($separator instanceof Markup) {
      $separator_safe = $separator->__toString();
    }
    elseif (is_array($separator)) {
      $separator_safe = \Drupal::service('renderer')->render($separator);
    }
    else {
      // @todo Log this error?
      $separator_safe = var_export($separator, TRUE);
    }
  }

  return implode($separator_safe, $pieces);
}
