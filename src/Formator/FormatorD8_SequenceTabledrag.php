<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\cu\AjaxCallback\AjaxCallback_ElementWithProperty;
use Drupal\cu\Formator\Util\ArrayMode;

class FormatorD8_SequenceTabledrag implements FormatorD8Interface {

  /**
   * @var \Donquixote\ObCK\Formula\Sequence\Formula_SequenceInterface
   */
  private $sequence;

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private $itemFormator;

  /**
   * @var int
   */
  private $arrayMode;

  /**
   * @var \Donquixote\ObCK\Translator\TranslatorInterface
   */
  private TranslatorInterface $translator;

  /**
   * @var array
   */
  private array $minStubConf;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Sequence\Formula_SequenceInterface $sequence
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   * @param \Donquixote\ObCK\Translator\TranslatorInterface $translator
   *
   * @return self
   *   Created instance.
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException Cannot create the item formator.
   */
  public static function create(
    Formula_SequenceInterface $sequence,
    IncarnatorInterface $incarnator,
    TranslatorInterface $translator
  ): self {
    return new self(
      $sequence,
      FormatorD8::fromFormula(
        $sequence->getItemFormula(),
        $incarnator),
      $translator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Sequence\Formula_SequenceInterface $sequence
   * @param \Drupal\cu\Formator\FormatorD8Interface $itemFormator
   * @param \Donquixote\ObCK\Translator\TranslatorInterface $translator
   */
  public function __construct(
    Formula_SequenceInterface $sequence,
    FormatorD8Interface $itemFormator,
    TranslatorInterface $translator
  ) {
    $this->sequence = $sequence;
    $this->itemFormator = $itemFormator;
    $this->translator = $translator;
    // @todo Support other array modes (assoc, mixed).
    $this->arrayMode = 0;
    // @todo Make this flexible, allow for minimum number of items.
    $this->minStubConf = [];
  }


  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_array($conf)) {
      $conf = $this->minStubConf;
    }
    elseif (!$this->arrayMode) {
      $conf = array_values($conf);
    }
    else {
      $conf = $this->filterAssocValues($conf);
    }

    $form = [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#type' => 'themekit_container',
      '#attributes' => ['class' => ['cu-sequence']],
      // Use closures so that the actual methods can remain private.
      '#process' => [function (array $element, FormStateInterface $form_state, array $form) {
        return $this->elementProcess($element, $form_state, $form);
      }],
      '#after_build' => [function (array $element, FormStateInterface $form_state) {
        return $this->elementAfterBuild($element, $form_state);
      }],
      '#value_callback' => function (array $element, $input, FormStateInterface $form_state) {
        return $this->elementValue($element, $input, $form_state);
      },
      '#pre_render' => [function (array $element) {
        return $this->elementPreRender($element);
      }],
    ];

    if ($label) {
      // Prepend a label, like in a form element.
      $form['label'] = [
        '#theme' => 'form_element_label',
        '#title' => $label,
        '#title_display' => 'above',
      ];
    }

    $form['#attached']['library'][] = 'cu/tabledrag';

    return $form;
  }

  /**
   * Called from a '#value_callback' callback.
   *
   * @param array $element
   *   The form element for this configurator.
   * @param array|mixed|false $input
   *   Raw value from form submission, or FALSE to use #default_value.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array|null
   *   Value to store in $form_state->values for this element.
   */
  private function elementValue(array $element, $input, FormStateInterface $form_state): ?array {

    if (!$form_state->getUserInput()) {
      // Return NULL to use default value.
      return NULL;
    }

    // @todo Ignore input, get combined value from nested elements?
    $vv = $form_state->getValue($element['#parents']);

    if (!\is_array($input)) {
      $input = $this->minStubConf;
    }
    elseif (!$this->arrayMode) {
      $input = array_values($input);
    }
    else {
      $input = $this->filterAssocValues($input);
    }

    // Set altered input for nested form elements.
    NestedArray::setValue(
      $form_state->getUserInput(),
      $element['#parents'],
      $input);

    // Create a reduced value for $form_state->values and $element['#value'].
    // More detail will be filled in by child elements.
    $value = array_fill_keys(array_keys($input), NULL);

    return $value;
  }

  /**
   * Called from a '#process' callback.
   *
   * @param array $element
   *   The form element for this configurator.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param array $form
   *   The complete form.
   *
   * @return array
   *   Processed element.
   */
  private function elementProcess(array $element, FormStateInterface $form_state, array $form): array {
    $control_name = 'cu-sequence-' . sha1(serialize($element['#parents']));

    $value = $element['#value'];

    $element['#control_name'] = $control_name;

    $uniqid = sha1(serialize($element['#parents']));
    # $element['#cu_sequence_ajax_wrapper_id'] = $uniqid;

    $element['#attached']['library'][] = 'cu/tabledrag';

    $element['items'] = [];
    $element['items']['#parents'] = $element['#parents'];

    $element['#prefix'] = '<div id="' . $uniqid . '" class="cu-sequence-ajax-wrapper">';
    $element['#suffix'] = '</div>';

    $parents = $element['#parents'];

    $ajax = [
      'callback' => new AjaxCallback_ElementWithProperty('#prefix', $element['#prefix']),
      'wrapper' => $uniqid,
      'method' => 'replace',
    ];

    foreach ($value as $delta => $item_value) {

      $element['items'][$delta] = [
        '#attributes' => ['class' => ['cu-sequence-item']],
      ];

      $element['items'][$delta]['handle'] = [
        '#markup' => '<!-- -->',
      ];

      $element['items'][$delta]['item'] = [
        '#theme_wrappers' => ['container'],
        '#attributes' => [
          'class' => ['cu-sequence-item-form'],
        ],
      ];

      // @todo What if $delta is a string?
      $item_label_str = $this->sequence
        ->deltaGetItemLabel($delta)
        ->convert($this->translator);

      if (1
        && isset($element['#title'])
        && '' !== ($sequenceLabel = $element['#title'])
      ) {
        $item_label_str = $sequenceLabel . ': ' . $item_label_str;
      }

      $item_form = $this->itemFormator->confGetD8Form($item_value, $item_label_str);
      $item_form['#parents'] = [...$element['#parents'], $delta];

      $element['items'][$delta]['item']['form']['conf'] = $item_form;

      $element['items'][$delta]['delete'] = [
        '#attributes' => ['class' => ['cu-sequence-delete-container']],
      ];

      $element['items'][$delta]['delete']['button'] = [
        '#type' => 'submit',
        '#parents' => [$control_name, '.delete', $delta],
        // The button name must be explicitly set, otherwise it will be 'op'.
        '#name' => $control_name . '[.delete][' . $delta . ']',
        '#value' => t('Remove'),
        '#attributes' => ['class' => ['cu-sequence-delete']],
        '#submit' => [
          function (array $form, FormStateInterface $form_state) use ($parents, $delta) {
            $input_all =& $form_state->getUserInput();
            $input =& NestedArray::getValue($input_all, $parents);
            if (!\is_array($input)) {
              $input = [];
            }
            unset($input[$delta]);
            if (!$this->arrayMode) {
              $input = array_values($input);
            }
            elseif ($this->arrayMode === ArrayMode::MIXED) {
              // Re-key items with numeric keys, but preserve string keys.
              $input = array_merge($input, []);
            }
            $form_state->setRebuild();
          },
        ],
        '#ajax' => $ajax,
        '#limit_validation_errors' => [],
      ];
    }

    if (empty($element['items'])) {
      $element['items']['#markup'] = '<!-- -->';
    }

    $new_item_label_str = $this->sequence
      ->deltaGetItemLabel(NULL)
      ->convert($this->translator);

    if (!$this->arrayMode) {
      $element['add_more_seq'] = [
        '#type' => 'submit',
        '#parents' => [$control_name, 'add_more_button'],
        // The button name must be explicitly set, otherwise it will be 'op'.
        '#name' => $control_name . '[add_more_button]',
        '#value' => $new_item_label_str,
        '#attributes' => ['class' => ['cu-sequence-add-more']],
        '#submit' => [
          static function (array $form, FormStateInterface $form_state) use ($parents) {
            $input_all =& $form_state->getUserInput();
            $input =& NestedArray::getValue($input_all, $parents);
            if (!\is_array($input)) {
              $input = [];
            }
            // Add an empty item.
            $input[] = NULL;
            $input = array_values($input);
            $form_state->setRebuild();
          },
        ],
        '#ajax' => $ajax,
        '#limit_validation_errors' => [],
      ];
    }
    else {
      $element['add_more_assoc'] = [
        '#type' => 'themekit_container',
        '#attributes' => [
          'class' => ['cu-assoc_add_more'],
        ],
      ];

      $element['add_more_assoc']['handle'] = [
        '#markup' => '',
      ];

      $element['add_more_assoc']['form']['key'] = [
        '#parents' => [$control_name, 'add_more_key'],
        /* @see \theme_textfield() */
        '#type' => 'textfield',
        '#title' => t('String key for new item'),
        '#default_value' => '',
        '#required' => FALSE,
        '#size' => 36,
      ];

      // Only validate the textfield if the 'Add more' button is clicked.
      if ($triggering_element = $form_state->getTriggeringElement()) {
        if ($triggering_element['#name'] === $control_name . '[add_more_button]') {
          $element['add_more_assoc']['form']['key']['#element_validate'] = [
            function (array $element, FormStateInterface $form_state) use ($value) {
              $add_more_key = $element['#value'];
              if ('' === $add_more_key) {
                if ($this->arrayMode !== ArrayMode::MIXED) {
                  $form_state->setError(
                    $element,
                    t('No key specified.'));
                }
              }
              elseif (NULL !== $error = $this->deltaGetValidationError($add_more_key)) {
                $form_state->setError($element, $error);
              }
              elseif (array_key_exists($add_more_key, $value)) {
                $form_state->setError(
                  $element,
                  t(
                    'Key @key already exists.',
                    [
                      '@key' => var_export($add_more_key, TRUE),
                    ]));
              }
            },
          ];
        }
      }

      $element['add_more_assoc']['add'] = [
        '#type' => 'themekit_container',
      ];

      $element['add_more_assoc']['add']['button'] = [
        '#type' => 'submit',
        '#parents' => [$control_name, 'add_more_button'],
        // The button name must be explicitly set, otherwise it will be 'op'.
        '#name' => $control_name . '[add_more_button]',
        '#value' => $new_item_label_str,
        '#attributes' => ['class' => ['cu-sequence-add-more']],
        '#submit' => [
          function (array $form, FormStateInterface $form_state) use ($parents, $control_name) {
            $input_all =& $form_state->getUserInput();
            $input =& NestedArray::getValue($input_all, $parents);
            if (!\is_array($input)) {
              $input = [];
            }
            $add_more_key =& $form_state->getValue([$control_name, 'add_more_key']);
            if ($add_more_key === '') {
              $input[] = NULL;
            }
            else {
              $input[$add_more_key] = NULL;
            }
            if ($this->arrayMode === ArrayMode::MIXED) {
              // Re-key items with numeric keys, but preserve string keys.
              $input = array_merge($input, []);
            }
            // Reset the add-more key for the next insertion.
            $add_more_key = '';
            $form_state->setRebuild();
          },
        ],
        '#ajax' => $ajax,
        '#limit_validation_errors' => [[$control_name]],
      ];
    }

    return $element;
  }

  /**
   * Called from the '#after_build' callback.
   *
   * @param array $element
   *   Original form element that was already processed.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Processed form element.
   */
  private function elementAfterBuild(array $element, FormStateInterface $form_state): array {
    // Normalize the form value.
    $value =& $form_state->getValue($element['#parents']);
    if ($value) {
      // All good.
      return $element;
    }
    // Set an empty array.
    $form_state->setValue($element['#parents'], []);
    return $element;
  }

  /**
   * Called from the '#pre_render' callback.
   *
   * @param array $element
   *   Original render element for this configurator form.
   *
   * @return array
   *   Processed render element.
   */
  private function elementPreRender(array $element): array {

    $rows = [];
    foreach (Element::children($element['items']) as $delta) {

      $item_element = $element['items'][$delta];
      $cells = [];
      foreach (Element::children($item_element) as $colname) {
        $cell_element = $item_element[$colname];
        $cell = ['data' => $cell_element];
        if (!empty($cell_element['#attributes'])) {
          $cell += $cell_element['#attributes'];
        }
        $cells[] = $cell;
      }

      $row = ['data' => $cells];
      if (!empty($item_element['#attributes'])) {
        $row += $item_element['#attributes'];
      }
      $row['class'][] = 'draggable';
      $rows[] = $row;
      unset($element['items'][$delta]);
    }

    if ($this->arrayMode) {
      // Insert the 'Add another item' as a table row.
      if (isset($element['add_more_assoc'])) {
        $add_more_element = $element['add_more_assoc'];
        $cells = [];
        foreach (Element::children($add_more_element) as $colname) {
          $cell_element = $add_more_element[$colname];
          $cell = ['data' => $cell_element];
          if (!empty($cell_element['#attributes'])) {
            $cell += $cell_element['#attributes'];
          }
          $cells[] = $cell;
        }

        $row = ['data' => $cells];
        if (!empty($add_more_element['#attributes'])) {
          $row += $add_more_element['#attributes'];
        }
        $rows[] = $row;
        unset($element['add_more_assoc']);
      }
    }

    if ([] === $rows) {
      $element['items'] = [
        '#type' => 'themekit_container',
        '#children' => '(' . t('no items') . ')',
      ];
      return $element;
    }

    $table_element = [
      '#theme' => 'table',
      // Disable disruptive behavior in bootstrap theme.
      '#context' => [
        'responsive' => FALSE,
        'hover' => FALSE,
      ],
      '#rows' => $rows,
      '#attributes' => $element['items']['#attributes'],
    ];

    $table_element['#attributes']['class'][] = 'cu-tabledrag';

    $element['items'] = $table_element;

    return $element;
  }

  /**
   * Filters an associative array of values, by removing illegal keys.
   *
   * @param mixed[] $values
   *   Unfiltered values.
   *
   * @return mixed[]
   *   Filtered values.
   */
  private function filterAssocValues(array $values): array {
    foreach ($values as $delta => $value) {
      if ($this->deltaGetValidationError($delta)) {
        unset($values[$delta]);
      }
    }
    if ($this->arrayMode === ArrayMode::MIXED) {
      // Re-key items with numeric keys, but preserve string keys.
      $values = array_merge($values, []);
    }
    return $values;
  }

  /**
   * Checks if an item key is valid.
   *
   * This only applies to the 'assoc' variant of this class.
   *
   * @param string|int $delta
   *   String key to validate.
   *
   * @return \Drupal\Component\Render\MarkupInterface|null
   *   NULL if ok, or a message if validation fails.
   */
  protected function deltaGetValidationError($delta): ?MarkupInterface {

    if ('' === $delta) {
      return t("Empty string key '' encountered.");
    }

    // Verify that the string key contains no disallowed characters.
    if (preg_match('@\W@', $delta, $m)) {
      return t(
        'String key @key with invalid character @char encountered.',
        [
          '@key' => var_export($delta, TRUE),
          '@char' => var_export($m[0], TRUE),
        ]);
    }

    return NULL;
  }

}
