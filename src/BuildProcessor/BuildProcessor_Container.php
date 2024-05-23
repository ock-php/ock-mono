<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProcessor;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromCall;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\Formula\Formula_ClassAttribute;
use Drupal\renderkit\Formula\Formula_TagName;
use Drupal\renderkit\Html\HtmlTagTrait;

class BuildProcessor_Container implements BuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @param string $tagName
   * @param string[] $classes
   *
   * @return self
   */
  #[OckPluginInstance('container', 'Container')]
  public static function create(
    #[OckOption('tag_name', 'Tag name')]
    #[OckFormulaFromCall([Formula_TagName::class, 'createFree'])]
    string $tagName = 'div',
    #[OckOption('classes', 'Classes')]
    #[OckFormulaFromCall([Formula_ClassAttribute::class, 'create'])]
    array $classes = [],
  ): self {
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
