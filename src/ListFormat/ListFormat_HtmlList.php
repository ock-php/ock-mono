<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\renderkit\Html\HtmlAttributesTrait;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;

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
   * @CfrPlugin("htmlList", @t("HTML list"))
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createFormula() {

    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        Formula_TagName::createForHtmlList(),
        Formula_ClassAttribute::create(),
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
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  public static function create($tagName = 'ul', array $classes = []) {

    $format = new self($tagName);

    if ([] === $classes) {
      return $format;
    }

    return $format->addClasses($classes);
  }

  /**
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  public static function ul(array $classes = []) {
    return self::create('ul', $classes);
  }

  /**
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
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
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds) {

    return [
      /* @see theme_themekit_list() */
      '#theme' => 'themekit_list',
      '#tag_name' => $this->tagName,
      '#attributes' => $this->attributes,
      '#items' => $builds,
    ];
  }
}
