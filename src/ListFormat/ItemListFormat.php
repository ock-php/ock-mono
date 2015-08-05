<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Html\HtmlAttributesTrait;

/**
 * Builds a render array for ul/li or ol/li lists.
 */
class ItemListFormat implements ListFormatInterface {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @var mixed[]
   */
  private $itemAttributes = array();

  /**
   * @param string $tagName
   */
  function __construct($tagName = 'ul') {
    $this->tagName = $tagName;
  }

  /**
   * @param string $class
   */
  function addItemClass($class) {
    $this->itemAttributes['class'][] = $class;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    $listBuild = array(
      '#theme' => 'renderkit_item_list',
      '#tag_name' => $this->tagName,
      '#attributes' => $this->attributes,
      '#item_attributes' => $this->itemAttributes,
    );
    foreach ($builds as $delta => $build) {
      $listBuild[$delta]['content'] = $build;
    }
    return $listBuild;
  }
}
