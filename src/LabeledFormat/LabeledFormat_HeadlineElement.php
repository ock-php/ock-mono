<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledFormat;

use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_Fixed;
use Drupal\Component\Utility\Html;

class LabeledFormat_HeadlineElement implements LabeledFormatInterface {

  /**
   * @var string
   */
  private $tagName;

  /**
   * @CfrPlugin(
   *   id = "headlineElement",
   *   label = "Headline element"
   * )
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function createFormula(): Formula_GroupValInterface {
    return Formula_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        Formula_Select_Fixed::createFlat(
          [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
          ]
        ),
      ],
      [
        t('Label element tag name'),
      ]
    );
  }

  /**
   * @param string $tagName
   */
  public function __construct($tagName = 'h2') {
    $this->tagName = $tagName;
  }

  /**
   * @param array $build
   *   Original render array (without label).
   * @param string $label
   *   A label / title.
   *
   * @return array
   *   Modified or wrapped render array with label.
   */
  public function buildAddLabel(array $build, $label): array {

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
