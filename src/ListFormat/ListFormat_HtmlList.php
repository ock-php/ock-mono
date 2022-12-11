<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlAttributesTrait;

/**
 * Builds a render array for ul/li or ol/li lists.
 */
class ListFormat_HtmlList implements ListFormatInterface {

  use HtmlAttributesTrait;

  /**
   * @CfrPlugin("htmlList", @t("HTML list"))
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {

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
  public static function create($tagName = 'ul', array $classes = []): ListFormat_HtmlList|ListFormatInterface {

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
  public static function ul(array $classes = []): ListFormat_HtmlList|ListFormatInterface {
    return self::create('ul', $classes);
  }

  /**
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  public static function ol(array $classes = []): ListFormat_HtmlList|ListFormatInterface {
    return self::create('ol', $classes);
  }

  /**
   * @param string $tagName
   */
  public function __construct(private $tagName = 'ul') {
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds): array {

    return [
      /* @see theme_themekit_list() */
      '#theme' => 'themekit_list',
      '#tag_name' => $this->tagName,
      '#attributes' => $this->attributes,
      '#items' => $builds,
    ];
  }
}
