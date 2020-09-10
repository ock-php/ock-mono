<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Form\D7\Util\D7SelectUtil;
use Donquixote\Cf\Form\D8\FormatorD8_Broken;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Optionlessness\OptionlessnessInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;

class FormatorD7_DrilldownSelect implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface
   */
  private $idSelectSchema;

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @var \Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  private $keysHelper;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @var (\Donquixote\Cf\Form\D7\FormatorD7Interface|false)[]
   */
  private $formators = [];

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldown
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_DrilldownInterface $drilldown, SchemaToAnythingInterface $schemaToAnything): ?self {

    $idSchema = $drilldown->getIdSchema();

    if (!$idSchema instanceof CfSchema_SelectInterface) {
      // Not supported. Write your own formator.
      return NULL;
    }

    $idToSchema = $drilldown->getIdToSchema();

    return new self(
      $idSchema,
      $idToSchema,
      DrilldownKeysHelper::fromSchema($drilldown),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $idSchema
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   * @param \Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(
    CfSchema_SelectInterface $idSchema,
    IdToSchemaInterface $idToSchema,
    DrilldownKeysHelperInterface $drilldownKeysHelper,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    $this->idSelectSchema = $idSchema;
    $this->idToSchema = $idToSchema;
    $this->keysHelper = $drilldownKeysHelper;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD7Interface {

    if (!$this->required) {
      return NULL;
    }

    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    list($id, $optionsConf) = $this->keysHelper->unpack($conf);

    $conf = $this->keysHelper->pack($id, $optionsConf);

    $_this = $this;

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cfr-drilldown']],
      '#tree' => TRUE,
      '_id' => D7SelectUtil::groupedOptionsBuildSelectElement(
        $this->getGroupedOptions(),
        $id,
        $label,
        $this->required
      ),
      '#input' => TRUE,
      '#title' => $label,
      '#default_value' => $conf,
      '#process' => [
        function (array $element, array &$form_state, array $form) use ($_this, $id, $optionsConf) {

          $element = $_this->processElement(
            $element,
            $form_state,
            $id,
            $optionsConf);

          $element = D7FormUtil::elementsBuildDependency(
            $element,
            $form_state,
            $form);

          return $element;
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this) {

          return $_this->elementAfterBuild($element, $form_state);
        },
      ],
    ];

    $form['_id']['#attributes']['class'][] = 'cfr-drilldown-select';

    return $form;
  }

  /**
   * @return string[][]
   */
  private function getGroupedOptions(): array {

    $groupedOptions = [];
    /** @var string[] $groupOptions */
    foreach ($this->idSelectSchema->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {
        $idSchema = $this->idToSchema->idGetSchema($id);
        if (NULL === $idSchema) {
          continue;
        }
        if (!$this->schemaIsOptionless($idSchema)) {
          $label .= '…';
        }
        $groupedOptions[$groupLabel][$id] = $label;
      }
    }

    return $groupedOptions;
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   *
   * @return bool
   */
  private function schemaIsOptionless(CfSchemaInterface $schema): bool {

    $optionlessnessOrNull = $this->schemaToAnything->schema(
      $schema,
      OptionlessnessInterface::class);

    return 1
      && NULL !== $optionlessnessOrNull
      && $optionlessnessOrNull instanceof OptionlessnessInterface
      && $optionlessnessOrNull->isOptionless();
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param string $defaultId
   * @param mixed $defaultOptionsConf
   *
   * @return array
   */
  private function processElement(
    array $element,
    array &$form_state,
    $defaultId,
    $defaultOptionsConf
  ): array {

    $value = $element['#value'];
    list($id /*, $options */) = $this->keysHelper->unpack($value);

    list($k0, $k1) = $this->keysHelper->getKeys();

    list($parents0, $parents1) = $this->keysHelper->buildTrails($element['#parents']);

    $element['_id']['#parents'] = $parents0;

    if ($id !== $defaultId) {
      $defaultOptionsConf = NULL;
    }

    $prevId = $value['_previous_id'] ?? null;

    if (NULL !== $k0 && NULL !== $prevId && $id !== $prevId && isset($form_state['input'])) {
      // Don't let values leak from one plugin to the other.
      if (NULL !== $k1) {
        ConfUtil::confUnsetNestedValue($form_state['input'], $parents1);
      }
      else {
        $input_id = ConfUtil::confExtractNestedValue($form_state['input'], $parents0);
        ConfUtil::confSetNestedValue($form_state['input'], $element['#parents'], [$k0 => $input_id]);
      }
    }

    $element['_options'] = $this->idConfBuildOptionsFormWrapper(
      $id,
      $defaultOptionsConf);

    $element['_options']['#parents'] = $parents1;

    $element['_options']['_previous_id'] = [
      '#type' => 'hidden',
      '#value' => $id,
      '#parents' => array_merge($element['#parents'], ['_previous_id']),
      '#weight' => -99,
    ];

    return $element;
  }

  /**
   * @param array $element
   * @param array $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state): array {

    ConfUtil::confUnsetNestedValue(
      $form_state['input'],
      array_merge($element['#parents'], ['_previous_id']));

    ConfUtil::confUnsetNestedValue(
      $form_state['values'],
      array_merge($element['#parents'], ['_previous_id']));

    return $element;
  }

  /**
   * @param string|null $id
   * @param mixed $subConf
   *
   * @return array
   */
  private function idConfBuildOptionsFormWrapper(
    $id,
    $subConf
  ): array {

    if (NULL === $id) {
      return [];
    }

    try {
      if (false === $subFormator = $this->idGetFormatorOrFalse($id)) {
        return [];
      }
    }
    catch (SchemaToAnythingException $e) {
      $subFormator = new FormatorD8_Broken($e->getMessage());
    }

    $optionsForm = $subFormator->confGetD7Form($subConf, NULL);

    if ([] === $optionsForm) {
      return [];
    }

    // @todo Unfortunately, #collapsible fieldsets do not play nice with Views UI.
    // See https://www.drupal.org/node/2624020
    # $options_form['#collapsed'] = TRUE;
    # $options_form['#collapsible'] = TRUE;
    return [
      '#type' => 'container',
      # '#type' => 'fieldset',
      # '#title' => $this->idGetOptionsLabel($id),
      '#attributes' => ['class' => ['cfrapi-child-options']],
      '#process' => [
        function(array $element /*, array &$form_state */) {
          if (isset($element['fieldset_content'])) {
            $element['fieldset_content']['#parents'] = $element['#parents'];
          }
          return $element;
        },
      ],
      'fieldset_content' => $optionsForm,
    ];
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|false
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private function idGetFormatorOrFalse($id) {
    return $this->formators[$id]
      ?? $this->formators[$id] = $this->idBuildFormatorOrFalse($id);
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|false
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private function idBuildFormatorOrFalse($id) {

    if (NULL === $schema = $this->idToSchema->idGetSchema($id)) {
      return FALSE;
    }

    if (NULL === $formator = D7FormSTAUtil::formator(
        $schema,
        $this->schemaToAnything
      )
    ) {
      return FALSE;
    }

    return $formator;
  }
}
