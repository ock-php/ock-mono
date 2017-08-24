<?php

namespace Drupal\renderkit8\LabeledFormat;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed;
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
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createSchema() {
    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        CfSchema_Select_Fixed::createFlat(
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
  public function buildAddLabel(array $build, $label) {

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
