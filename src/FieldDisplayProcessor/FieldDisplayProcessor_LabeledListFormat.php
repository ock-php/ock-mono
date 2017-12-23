<?php

namespace Drupal\renderkit8\FieldDisplayProcessor;

use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface;

class FieldDisplayProcessor_LabeledListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @CfrPlugin("labeledListFormatPlus", "Labeled list format +")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema() {
    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_IfaceWithContext(LabeledListFormatInterface::class),
        new CfSchema_Boolean_YesNo(),
      ],
      [
        t('Labeled list format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(LabeledListFormatInterface $labeledListFormat, $withFieldClasses = FALSE) {
    $fieldDisplayProcessor = new self($labeledListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat) {
    $this->labeledListFormat = $labeledListFormat;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element) {

    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }

    return $this->labeledListFormat->build($builds, $element['#title']);
  }
}
