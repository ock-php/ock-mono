<?php

namespace Drupal\renderkit8\FieldDisplayProcessor;

use Donquixote\Cf\Schema\Boolean\CfSchema_Boolean_YesNo;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface;

class FieldDisplayProcessor_LabeledEntityDisplayListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  private $labeledEntityDisplayListFormat;

  /**
   * @param \Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
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
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema() {
    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_IfaceWithContext(LabeledEntityDisplayListFormatInterface::class),
        new CfSchema_Boolean_YesNo(),
      ],
      [
        t('Labeled list format'),
        t('Add field classes'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat
   * @param bool $withFieldClasses
   *
   * @return \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  public static function create(LabeledEntityDisplayListFormatInterface $labeledEntityDisplayListFormat, $withFieldClasses = FALSE) {
    $fieldDisplayProcessor = new self($labeledEntityDisplayListFormat);
    if ($withFieldClasses) {
      $fieldDisplayProcessor = new FieldDisplayProcessor_FieldClasses($fieldDisplayProcessor);
    }
    return $fieldDisplayProcessor;
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

    $entityType = $element['#entity_type'];
    $entity = $element['#object'];

    # return [];

    return $this->labeledEntityDisplayListFormat->build($builds, $entityType, $entity, $element['#title']);
  }
}
