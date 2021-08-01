<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Emptiness\EmptinessInterface;
use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\ConfUtil;
use Donquixote\OCUI\Util\StaUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_SequenceWithEmptiness implements FormatorD8Interface {

  /**
   * @var \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface
   */
  private $sequenceFormula;

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8Interface
   */
  private $optionalItemFormator;

  /**
   * @var \Donquixote\OCUI\Emptiness\EmptinessInterface
   */
  private $itemEmptiness;

  /**
   * @var \Donquixote\OCUI\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createOrNull(
    Formula_SequenceInterface $formula,
    FormulaToAnythingInterface $formulaToAnything,
    TranslatorInterface $translator
  ): ?FormatorD8_SequenceWithEmptiness {

    if (NULL === $emptiness = StaUtil::emptinessOrNull(
      $formula->getItemFormula(),
      $formulaToAnything)
    ) {
      # kdpm($formula->getItemFormula(), 'no emptiness found.');
      return NULL;
    }

    $optionalFormator = FormatorD8::optional(
      $formula->getItemFormula(),
      $formulaToAnything
    );

    if (NULL === $optionalFormator) {
      # kdpm('Sorry.');
      return NULL;
    }

    return new self(
      $formula,
      $optionalFormator,
      $emptiness,
      $translator);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $sequenceFormula
   * @param \Donquixote\OCUI\Form\D8\FormatorD8Interface $optionalItemFormator
   * @param \Donquixote\OCUI\Emptiness\EmptinessInterface $itemEmptiness
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   */
  public function __construct(
    Formula_SequenceInterface $sequenceFormula,
    FormatorD8Interface $optionalItemFormator,
    EmptinessInterface $itemEmptiness,
    TranslatorInterface $translator
  ) {
    $this->sequenceFormula = $sequenceFormula;
    $this->optionalItemFormator = $optionalItemFormator;
    $this->itemEmptiness = $itemEmptiness;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $_this = $this;

    if (NULL !== $label && '' !== $label && 0 !== $label) {
      $form = [
        '#type' => 'container',
        '#title' => $label,
      ];
    }
    else {
      $form = [
        '#type' => 'container',
      ];
    }

    $form['#attributes']['class'][] = 'faktoria-child-options';

    $form += [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#process' => [
        function (array $element /*, array &$form_state */) use ($_this) {
          return $_this->elementProcess($element);
        },
      ],
      '#after_build' => [
        function (array $element, FormStateInterface $form_state) use ($_this) {
          return $_this->elementAfterBuild(
            $element,
            $form_state);
        },
      ],
    ];

    $form['#attached']['library'][] = 'faktoria/form';

    return $form;
  }

  /**
   * @param array $element
   *
   * @return array
   */
  private function elementProcess(array $element): array {

    $conf = $element['#value'];

    if (!\is_array($conf)) {
      $conf = [];
    }

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      if ($this->itemEmptiness->confIsEmpty($itemConf)) {
        // Skip empty items.
        continue;
      }

      $itemLabel = $this->deltaGetItemLabel($delta, $this->translator);

      $element[$delta] = $this->optionalItemFormator->confGetD8Form(
        $itemConf, $itemLabel);
    }

    $newItemLabel = $this->deltaGetItemLabel(NULL, $this->translator);

    // Element for new item.
    $element[] = $this->optionalItemFormator->confGetD8Form(
      NULL, $newItemLabel);

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, TranslatorInterface $helper): string {
    return $this->sequenceFormula->deltaGetItemLabel($delta);

    /*
    return (NULL === $delta)
      ? t('New item')
      : t('Item !n', ['!n' => '#' . check_plain($delta)]);
    */
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    FormStateInterface $form_state): array {

    $conf = ConfUtil::confExtractNestedValue(
      $form_state->getValues(),
      $element['#parents']);

    if (!\is_array($conf)) {
      $conf = [];
    }

    # $itemFormula = $this->formula->getItemFormula();

    foreach ($conf as $delta => $itemConf) {
      if ($this->itemEmptiness->confIsEmpty($itemConf)) {
        unset($conf[$delta]);
      }
    }

    $conf = array_values($conf);

    ConfUtil::confSetNestedValue(
      $form_state->getValues(),
      $element['#parents'],
      $conf);

    if (isset($element['#title']) && '' !== $element['#title']) {
      $element['#theme_wrappers'][] = 'form_element';
    }

    return $element;
  }
}
