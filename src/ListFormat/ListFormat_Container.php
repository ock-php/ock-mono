<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromCall;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlTagTrait;

class ListFormat_Container implements ListFormatInterface {

  use HtmlTagTrait;

  /**
   * @param string $tagName
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  #[OckPluginInstance('container', 'Outer container element')]
  public static function create(
    #[OckOption('tag', 'Tag name')]
    #[OckFormulaFromCall([Formula_TagName::class, 'createForContainer'])]
    string $tagName,
    #[OckOption('classes', 'Classes')]
    #[OckFormulaFromCall([Formula_ClassAttribute::class, 'create'])]
    array $classes,
  ): ListFormatInterface {
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
  public function buildList(array $builds): array {
    return $this->buildContainer() + $builds;
  }
}
