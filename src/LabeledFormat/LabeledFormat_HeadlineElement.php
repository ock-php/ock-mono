<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledFormat;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromCall;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\Component\Utility\Html;
use Drupal\renderkit\Formula\Formula_TagName;

#[OckPluginInstance('headlineElement', 'Headline element')]
class LabeledFormat_HeadlineElement implements LabeledFormatInterface {

  /**
   * Constructor.
   *
   * @param string $tagName
   */
  public function __construct(
    #[OckOption('tag', 'Label element tag name')]
    #[OckFormulaFromCall([Formula_TagName::class, 'createForTitle'])]
    private readonly string $tagName = 'h2'
  ) {}

  /**
   * @param array $build
   *   Original render array (without label).
   * @param string $label
   *   A label / title.
   *
   * @return array
   *   Modified or wrapped render array with label.
   */
  public function buildAddLabel(array $build, string $label): array {

    // @todo Use proper render element setup.
    $label_markup = ''
      . '<' . $this->tagName . '>'
      . Html::escape($label)
      . '</' . $this->tagName . '>';

    return [
      'label' => [
        '#markup' => $label_markup,
      ],
      'contents' => $build,
    ];
  }
}
