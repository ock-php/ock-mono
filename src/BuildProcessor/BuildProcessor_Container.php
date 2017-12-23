<?php

namespace Drupal\renderkit8\BuildProcessor;

use Donquixote\Cf\Schema\Callback\CfSchema_Callback;
use Drupal\renderkit8\Html\HtmlTagTrait;
use Drupal\renderkit8\Schema\CfSchema_ClassAttribute;
use Drupal\renderkit8\Schema\CfSchema_TagName;

class BuildProcessor_Container implements BuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin("container", @t("Container"))
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_Callback::fromStaticMethod(__CLASS__, 'create')
      ->withParamSchema(
        0,
        CfSchema_TagName::createFree(),
        t('Tag name'))
      ->withParamSchema(
        1,
        CfSchema_ClassAttribute::create(),
        t('Classes'));
  }

  /**
   * @_CfrPlugin("container", @t("Container"))
   * @_ParamSchemas(
   *   0 = "renderkit8.tagName.container",
   *   1 = "renderkit8.classes"
   * )
   * @_ParamLabels(
   *   0 = @t("Tag name")
   *   1 = @t("Classes")
   * )s
   * @_ParamSchema(0, "renderkit8.tagName.container", @t("Tag name"))
   *
   * @param string $tagName
   * @param array $classes
   *
   * @return \Drupal\renderkit8\BuildProcessor\BuildProcessor_Container
   */
  public static function create($tagName = 'div', array $classes = []) {
    return (new self())
      ->setTagName($tagName)
      ->addClasses($classes);
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  public function process(array $build) {
    return $this->buildContainer() + ['content' => $build];
  }
}
