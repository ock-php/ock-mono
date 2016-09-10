<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\cfrapi\Configurator\Bool\Configurator_Checkbox;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
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
   * @CfrPlugin("labeledEntityDisplayListFormatPlus", "Labeled entity display list format +")
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {
    return Configurator_CallbackConfigurable::createFromClassStaticMethod(
      __CLASS__,
      'create',
      [
        cfrplugin()->interfaceGetConfigurator(LabeledEntityDisplayListFormatInterface::class),
        new Configurator_Checkbox(),
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
