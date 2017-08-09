<?php

namespace Drupal\renderkit8\ListFormat;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\Html\HtmlAttributesTrait;
use Drupal\renderkit8\Schema\CfSchema_ClassAttribute;
use Drupal\renderkit8\Schema\CfSchema_TagName;

/**
 * Builds a render array for ul/li or ol/li lists.
 */
class ListFormat_HtmlList implements ListFormatInterface {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @var mixed[]
   */
  private $itemAttributes = [];

  /**
   * @CfrPlugin("htmlList", @t("HTML list"))
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        CfSchema_TagName::createForHtmlList(),
        CfSchema_ClassAttribute::create(),
      ],
      [
        t('List type'),
        t('Classes'),
      ]);
  }

  /**
   * @param string $tagName
   * @param string[] $classes
   *
   * @return \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  public static function create($tagName = 'ul', array $classes = []) {
    $format = new self($tagName);
    foreach ($classes as $class) {
      $format->addClass($class);
    }
    return $format;
  }

  /**
   * @param string[] $classes
   *
   * @return \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  public static function ul(array $classes = []) {
    return self::create('ul', $classes);
  }

  /**
   * @param string[] $classes
   *
   * @return \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  public static function ol(array $classes = []) {
    return self::create('ol', $classes);
  }

  /**
   * @param string $tagName
   */
  public function __construct($tagName = 'ul') {
    $this->tagName = $tagName;
  }

  /**
   * @param string $class
   *
   * @return $this
   */
  public function addItemClass($class) {
    $this->itemAttributes['class'][] = $class;
    return $this;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds) {
    $listBuild = [
      /* @see theme_themekit_item_list() */
      '#theme' => 'themekit_item_list',
      '#tag_name' => $this->tagName,
      '#attributes' => $this->attributes,
      '#item_attributes' => $this->itemAttributes,
    ];
    foreach ($builds as $delta => $build) {
      $listBuild[$delta]['content'] = $build;
    }
    return $listBuild;
  }
}
