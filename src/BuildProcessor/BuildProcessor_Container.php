<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProcessor;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Callback\Formula_Callback;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlTagTrait;

class BuildProcessor_Container implements BuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin("container", @t("Container"))
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {

    return Formula_Callback::fromStaticMethod(__CLASS__, 'create')
      ->withParamFormula(
        0,
        Formula_TagName::createFree(),
        t('Tag name'))
      ->withParamFormula(
        1,
        Formula_ClassAttribute::create(),
        t('Classes'));
  }

  /**
   * @_CfrPlugin("container", @t("Container"))
   * @_ParamFormulas(
   *   0 = "renderkit.tagName.container",
   *   1 = "renderkit.classes"
   * )
   * @_ParamLabels(
   *   0 = @t("Tag name")
   *   1 = @t("Classes")
   * )s
   * @_ParamFormula(0, "renderkit.tagName.container", @t("Tag name"))
   *
   * @param string $tagName
   * @param array $classes
   *
   * @return self
   */
  public static function create($tagName = 'div', array $classes = []): self {
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
  public function process(array $build): array {
    return $this->buildContainer() + ['content' => $build];
  }
}
