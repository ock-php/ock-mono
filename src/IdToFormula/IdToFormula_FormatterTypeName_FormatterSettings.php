<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\renderkit\Formula\Formula_FieldFormatterId;
use Drupal\renderkit\Formula\Formula_FieldFormatterSettings;

/**
 * Object to gets a formatter settings form for a formatter id.
 */
class IdToFormula_FormatterTypeName_FormatterSettings implements IdToFormulaInterface {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface
   */
  private $fieldDefinition;

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private $formatterPluginManager;

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   *
   * @return \Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface
   */
  public static function createDrilldownValFormula(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ): Formula_DrilldownValInterface {
    return Formula_DrilldownVal::createArrify(
      self::createDrilldownFormula(
        $formatterPluginManager,
        $fieldDefinition));
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldownFormula(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ): Formula_DrilldownInterface {

    return Formula_Drilldown::create(
      new Formula_FieldFormatterId(
        $formatterPluginManager,
        $fieldDefinition->getType()),
      new self(
        $formatterPluginManager,
        $fieldDefinition))
      ->withKeys('type', 'settings');
  }

  /**
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   */
  public function __construct(
    FormatterPluginManager $formatterPluginManager,
    FieldDefinitionInterface $fieldDefinition
  ) {
    $this->formatterPluginManager = $formatterPluginManager;
    $this->fieldDefinition = $fieldDefinition;
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($id): ?FormulaInterface {

    $formatter = $this->getFormatterInstance($id);

    if (NULL === $formatter) {
      return NULL;
    }

    return new Formula_FieldFormatterSettings($formatter);
  }

  /**
   * @param $formatterTypeName
   *
   * @return \Drupal\Core\Field\FormatterInterface|null
   */
  private function getFormatterInstance($formatterTypeName): ?FormatterInterface {

    $settings = $this->formatterPluginManager->getDefaultSettings($formatterTypeName);

    $options = [
      'field_definition' => $this->fieldDefinition,
      'configuration' => [
        'type' => $formatterTypeName,
        'settings' => $settings,
        'label' => '',
        'weight' => 0,
      ],
      'view_mode' => '_custom',
    ];

    return $this->formatterPluginManager->getInstance($options);
  }
}
