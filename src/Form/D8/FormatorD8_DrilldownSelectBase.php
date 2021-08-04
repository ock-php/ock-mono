<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\OCUI\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\OCUI\Form\D8\Util\D8FormUtil;
use Donquixote\OCUI\Form\D8\Util\D8SelectUtil;
use Donquixote\OCUI\Formula\Select\Formula_Select_Fixed;
use Donquixote\OCUI\Formula\Select\Formula_SelectInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\OCUI\Translator\Translator;
use Donquixote\OCUI\Util\ConfUtil;
use Drupal\Core\Form\FormStateInterface;

abstract class FormatorD8_DrilldownSelectBase implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Formula_SelectInterface
   */
  private $idSelectFormula;

  /**
   * @var \Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  private $keysHelper;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    DrilldownKeysHelperInterface $drilldownKeysHelper
  ) {
    $this->idSelectFormula = $idFormula;
    $this->keysHelper = $drilldownKeysHelper;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {

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
  public function confGetD8Form($conf, $label): array {

    list($id, $optionsConf) = $this->keysHelper->unpack($conf);

    $conf = $this->keysHelper->pack($id, $optionsConf);

    $translator = new Translator(new TranslatorLookup_Passthru());

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['faktoria-drilldown']],
      '#tree' => TRUE,
      '_id' => D8SelectUtil::optionsFormulaBuildSelectElement(
        $this->buildEnhancedSelectFormula(),
        $translator,
        $id,
        $label,
        $this->required
      ),
      '#input' => TRUE,
      '#title' => $label,
      '#default_value' => $conf,
      '#process' => [
        function (array $element, FormStateInterface $form_state, array $form) use ($id, $optionsConf) {

          $element = $this->processElement(
            $element,
            $form_state,
            $id,
            $optionsConf);

          $element = D8FormUtil::elementsBuildDependency(
            $element,
            $form_state,
            $form);

          return $element;
        },
      ],
      '#after_build' => [
        function (array $element, FormStateInterface $form_state) {

          return $this->elementAfterBuild($element, $form_state);
        },
      ],
    ];

    $form['_id']['#attributes']['class'][] = 'faktoria-drilldown-select';

    $form['#attached']['library'][] = 'faktoria/form';

    return $form;
  }

  /**
   * Builds a modified select formula with '…' appended to some options.
   *
   * @return \Donquixote\OCUI\Formula\Select\Formula_SelectInterface
   */
  private function buildEnhancedSelectFormula(): Formula_SelectInterface {
    $grouped_options = [];
    $grouped_options[''] = $this->buildEnhancedOptionsInGroup(NULL);
    $groups = $this->idSelectFormula->getOptGroups();
    foreach ($groups as $group_id => $group_label) {
      $grouped_options[$group_id] = $this->buildEnhancedOptionsInGroup($group_id);
    }
    return new Formula_Select_Fixed($grouped_options, $groups);
  }

  /**
   * @param string|null $group_id
   *
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  private function buildEnhancedOptionsInGroup(?string $group_id): array {
    $options = [];
    foreach ($this->idSelectFormula->getOptions($group_id) as $id => $option_label) {
      $options[$id] = $this->idIsOptionless($id)
        ? $option_label
        : Text::s('@label…', ['@label' => $option_label]);
    }
    return $options;
  }

  /**
   * @param string $id
   *   Id of the sub-formula.
   *
   * @return bool
   *   TRUE, if the sub-formula is optionless.
   */
  abstract protected function idIsOptionless(string $id): bool;

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param string|null $defaultId
   * @param mixed $defaultOptionsConf
   *
   * @return array
   */
  private function processElement(
    array $element,
    FormStateInterface $form_state,
    ?string $defaultId,
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

    if (NULL !== $k0 && NULL !== $prevId && $id !== $prevId && ($input = &$form_state->getUserInput())) {
      // Don't let values leak from one plugin to the other.
      if (NULL !== $k1) {
        ConfUtil::confUnsetNestedValue($input, $parents1);
      }
      else {
        $input_id = ConfUtil::confExtractNestedValue($input, $parents0);
        ConfUtil::confSetNestedValue($input, $element['#parents'], [$k0 => $input_id]);
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
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, FormStateInterface $form_state): array {

    ConfUtil::confUnsetNestedValue(
      $form_state->getUserInput(),
      array_merge($element['#parents'], ['_previous_id']));

    ConfUtil::confUnsetNestedValue(
      $form_state->getValues(),
      array_merge($element['#parents'], ['_previous_id']));

    return $element;
  }

  /**
   * @param string|null $id
   * @param mixed $subConf
   *
   * @return array
   */
  private function idConfBuildOptionsFormWrapper(?string $id, $subConf): array {

    if (NULL === $id) {
      return [];
    }

    $optionsForm = $this->idGetSubform($id, $subConf);

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
      '#attributes' => ['class' => ['faktoria-child-options']],
      '#process' => [
        static function (array $element) {
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
   *   Chosen drilldown id.
   * @param mixed $subConf
   *   Sub-configuration for the chosen drilldown option.
   *
   * @return array
   *   Sub-form for the chosen drilldown option.
   */
  abstract protected function idGetSubform(string $id, $subConf): array;

}
