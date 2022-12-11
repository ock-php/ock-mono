<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\Ock\Formula\Iface\Formula_IfaceWithContext;
use Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class FieldDisplayProcessor_LabeledEntityDisplayListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  private $labeledEntityDisplayListFormat;

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   */
  public function __construct(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat) {
    $this->labeledEntityDisplayListFormat = $labeledEntityDisplayListFormat;
  }

  /**
   * @CfrPlugin(
   *   id = "labeledEntityDisplayListFormatPlus",
   *   label = "Labeled entity display list format +"
   * )
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {
    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new Formula_IfaceWithContext(LabeledEntityDisplayListFormatInterface::class),
        new Formula_Boolean_YesNo(),
      ],
      [
        t('Labeled list format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat, $withFieldClasses = FALSE): FieldDisplayProcessorInterface|FieldDisplayProcessor_FieldClasses|FieldDisplayProcessor_LabeledEntityDisplayListFormat {
    $fieldDisplayProcessor = new self($labeledEntityDisplayListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
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

    $entityType = $element['#entity_type'];
    $entity = $element['#object'];

    # return [];

    return $this->labeledEntityDisplayListFormat->build($builds, $entityType, $entity, $element['#title']);
  }
}
