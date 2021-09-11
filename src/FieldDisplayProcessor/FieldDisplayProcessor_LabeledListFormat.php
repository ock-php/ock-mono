<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\Ock\Formula\Iface\Formula_IfaceWithContext;
use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

class FieldDisplayProcessor_LabeledListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @CfrPlugin("labeledListFormatPlus", "Labeled list format +")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {
    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new Formula_IfaceWithContext(LabeledListFormatInterface::class),
        new Formula_Boolean_YesNo(),
      ],
      [
        t('Labeled list format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(LabeledListFormatInterface $labeledListFormat, $withFieldClasses = FALSE) {
    $fieldDisplayProcessor = new self($labeledListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
  }

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat) {
    $this->labeledListFormat = $labeledListFormat;
  }

  /**
   * @param array|array[][] $element
   *   Format:
   *     ['#theme' => 'field', '#items' => [..], ..]
   *     $['#items'][$delta] = $item
   *
   * @return array
   */
  public function process(array $element): array {

    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }

    return $this->labeledListFormat->build($builds, $element['#title']);
  }
}
