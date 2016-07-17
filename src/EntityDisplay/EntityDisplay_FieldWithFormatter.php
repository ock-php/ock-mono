<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\cfrapi\Configurator\Group\Configurator_GroupReparentV2V;
use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConf;
use Drupal\renderkit\ConfiguratorMap\ConfiguratorMap_FieldDisplaySettings;
use Drupal\renderkit\EnumMap\EnumMap_FieldName;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;
use Drupal\renderkit\Helper\EntityTypeFieldDisplayHelper;
use Drupal\renderkit\ValueToValue\ValueToValue_FieldEntityDisplay;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityDisplay_FieldWithFormatter extends EntitiesDisplayBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $display;

  /**
   * @var \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface|null
   */
  private $fieldDisplayProcessor;

  /**
   * @var string|null
   */
  private $langcode;

  /**
   * @CfrPlugin(
   *   id = "fieldWithFormatter",
   *   label = "Field with formatter"
   * )
   *
   * @param string|null $entityType
   *   Contextual parameter.
   * @param string|null $bundleName
   *   Contextual parameter.
   *
   * @return \Drupal\cfrfamily\Configurator\Composite\Configurator_IdConf
   */
  static function createConfigurator($entityType = NULL, $bundleName = NULL) {
    $legend = new EnumMap_FieldName(NULL, $entityType, $bundleName);
    $fieldnameToConfigurator = new ConfiguratorMap_FieldDisplaySettings();

    $fieldConfigurator = (new Configurator_IdConf($legend, $fieldnameToConfigurator))
      ->withKeys('field', 'display');

    $labelDisplayConfigurator = Configurator_LegendSelect::createFromOptions(
      array(
        'above' => t('Above'),
        'inline' => t('Inline'),
        'hidden' => '<' . t('Hidden') . '>',
      ),
      'hidden');

    $processorConfigurator = cfrplugin()->interfaceGetOptionalConfigurator(FieldDisplayProcessorInterface::class);

    return (new Configurator_GroupReparentV2V)
      ->keySetConfigurator('field', $fieldConfigurator, t('Field'))
      ->keySetConfigurator('label', $labelDisplayConfigurator, t('Label display'))
      ->keySetConfigurator('processor', $processorConfigurator, t('Field display processor'))
      ->keySetParents('processor', array('processor'))
      ->keySetParents('label', array('display', 'label'))
      ->keySetParents('field', array())
      ->setValueToValue(new ValueToValue_FieldEntityDisplay('field', 'display', 'processor'))
    ;
  }

  /**
   * @param string $field_name
   * @param array $display
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $fieldDisplayProcessor
   * @param string $langcode
   */
  function __construct($field_name, array $display = array(), FieldDisplayProcessorInterface $fieldDisplayProcessor = NULL, $langcode = NULL) {
    $this->fieldName = $field_name;
    $this->display = $display + array('label' => 'hidden');
    $this->fieldDisplayProcessor = $fieldDisplayProcessor;
    $this->langcode = $langcode;
  }

  /**
   * Sets the field formatter.
   *
   * @param string $name
   *   The machine name of the field formatter.
   * @param array $settings
   *   The formatter settings.
   *
   * @return $this
   */
  function setFormatter($name, array $settings = NULL) {
    $this->display['type'] = $name;
    if (isset($settings)) {
      $this->display['settings'] = $settings;
    }
    return $this;
  }

  /**
   * @param string $label_position
   *   The default implementation supports 'above', 'inline' and 'hidden'.
   *   Default in core is 'above', but default here is 'hidden'.
   */
  function setLabelPosition($label_position) {
    $this->display['label'] = $label_position;
  }

  /**
   * @param string $entityType
   * @param array $entities
   *
   * @return array
   * @throws \EntityMalformedException
   */
  function buildEntities($entityType, array $entities) {
    $helper = EntityTypeFieldDisplayHelper::create($entityType, $this->fieldName, $this->display, $this->langcode);
    $builds = $helper->buildMultipleByDelta($entities);
    if (NULL !== $this->fieldDisplayProcessor) {
      foreach ($builds as $delta => $build) {
        $builds[$delta] = $this->fieldDisplayProcessor->process($build);
      }
    }
    return $builds;
  }

}
