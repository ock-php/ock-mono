<?php
declare(strict_types=1);

namespace Drupal\renderkit8\ListFormat;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\Html\HtmlTagTrait;
use Drupal\renderkit8\Schema\CfSchema_ClassAttribute;
use Drupal\renderkit8\Schema\CfSchema_TagName;

class ListFormat_Container implements ListFormatInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin("container", @t("Outer container element"))
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        CfSchema_TagName::createForContainer(),
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
  public static function create($tagName, array $classes) {
    return (new self())
      ->setTagName($tagName)
      ->addClasses($classes);
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
    return $this->buildContainer() + $builds;
  }
}
