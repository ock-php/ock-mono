<?php
declare(strict_types=1);

use Drupal\Core\Template\Attribute;
use Drupal\themekit\Util\ElementUtil;

/**
 * Implements hook_preprocess_HOOK().
 */
function template_preprocess_themekit_link_wrapper(array &$variables): void {
  $element = $variables['element'];
  $variables['url'] = $element['#url']->toString();
  $variables['children'] = $element['#children'];
  $variables['options'] = $element['#options'] ?? [];
  $variables['options'] += ['attributes' => []];
  $variables['options']['attributes'] += $element['#attributes'] ?? [];
}

/**
 * Implements hook_preprocess_HOOK().
 */
function template_preprocess_themekit_item_containers(array &$variables): void {
  $element = $variables['element'];
  $variables['item_tag_name'] = $element['#item_tag_name'] ?? 'div';
  $variables['item_attributes'] = new Attribute($element['#item_attributes'] ?? []);
  $variables['items'] = $element['#items']
    ?? ElementUtil::childElements($element);
}

/**
 * Implements hook_preprocess_HOOK().
 */
function template_preprocess_themekit_list(array &$variables): void {
  $element = $variables['element'];
  $variables['tag_name'] = $element['#tag_name'] ?? 'ul';
  $variables['items'] = $element['#items']
    ?? ElementUtil::childElements($element);
}

/**
 * Implements hook_preprocess_HOOK().
 */
function template_preprocess_themekit_separator_list(array &$variables): void {
  $element = $variables['element'];
  $variables['separator'] = $element['#separator'] ?? '';
  $variables['items'] = $element['#items']
    ?? ElementUtil::childElements($element);
}
