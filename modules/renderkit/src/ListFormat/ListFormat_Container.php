<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlTagTrait;
use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

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
   * {@inheritdoc}
   */
  public function buildList(array $builds): array {
    return $this->buildContainer() + $builds;
  }
}
