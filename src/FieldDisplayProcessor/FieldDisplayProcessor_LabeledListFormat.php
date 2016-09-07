<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\cfrapi\Configurator\Bool\Configurator_Checkbox;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

class FieldDisplayProcessor_LabeledListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @CfrPlugin("labeledListFormatPlus", "Labeled list format +")
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {
    return Configurator_CallbackConfigurable::createFromClassStaticMethod(
      __CLASS__,
      'create',
      [
        cfrplugin()->interfaceGetConfigurator(LabeledListFormatInterface::class),
        new Configurator_Checkbox(),
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
