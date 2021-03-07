<?php

namespace Donquixote\OCUI\Form\D8;

use Donquixote\OCUI\Emptiness\EmptinessInterface;
use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\T;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Form;

/**
 * Base class for sequence and assoc.
 */
abstract class FormatorD8_TabledragBase implements FormatorD8Interface {

  const ASSOC = FALSE;

  /**
   * @var \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface
   */
  private $sequenceFormula;

  /**
   * @var \Donquixote\OCUI\Form\D8\FormatorD8Interface
   */
  private $itemFormator;

  /**
   * @var \Donquixote\OCUI\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $sequenceFormula
   * @param \Donquixote\OCUI\Form\D8\FormatorD8Interface $optionalItemFormator
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   */
  public function __construct(
    Formula_SequenceInterface $sequenceFormula,
    FormatorD8Interface $optionalItemFormator,
    TranslatorInterface $translator
  ) {
    $this->sequenceFormula = $sequenceFormula;
    $this->itemFormator = $optionalItemFormator;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_array($conf)) {
      // Always start with one stub item, if this is a serial sequence.
      $conf = static::ASSOC ? [] : [NULL];
    }

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

    $form['#attributes']['class'][] = 'cfrapi-sequence';
    $form['#attributes']['class'][] = 'faktoria-child-options';

    $form += [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#process' => [function (array $element, FormStateInterface $form_state, array &$form) use ($conf) {
        $form_build_id = $form['form_build_id']['#value'];
        return $this->elementProcess($element, $conf, $form_build_id);
      }],
      '#after_build' => [function (array $element, FormStateInterface $form_state) {
        return $this->elementAfterBuild($element, $form_state);
      }],
      '#value_callback' => T::c('_cfrapi_generic_value_callback'),
      '#cfrapi_value_callback' => function (array $element, $input, array &$form_state) {
        return $this->elementValue($element, $input, $form_state);
      },
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param array|mixed|false $input
   *   Raw value from form submission, or FALSE to use #default_value.
   * @param array $form_state
   *
   * @return array|bool|mixed
   */
  private function elementValue(array $element, $input, array &$form_state) {

    if (false === $input) {
      return isset($element['#default_value'])
        ? $element['#default_value']
        : [];
    }

    if (!\is_array($input)) {
      if (!static::ASSOC) {
        // Always start with one item.
        $input = [NULL];
      }
      else {
        $input = [];
      }
    }

    unset($input['.delete']);
    unset($input['.add_more']);
    if (!static::ASSOC) {
      $input = array_values($input);
    }
    else {
      $element_name = $element['#name'];
      $form_state['cfrapi-add_more_keys'][$element_name] = isset($input['.add_more_key'])
        ? $input['.add_more_key']
        : '';
      unset($input['.add_more_key']);
    }
    NestedArray::setValue($form_state['input'], $element['#parents'], $input);
    NestedArray::setValue($form_state['values'], $element['#parents'], $input);
    return $input;
  }

  /**
   * @param array $element
   * @param array $conf
   * @param string $form_build_id
   *
   * @return array
   */
  private function elementProcess(array $element, array $conf, string $form_build_id) {

    $value = $element['#value'];
    if (!\is_array($value)) {
      $value = [];
    }
    elseif (!static::ASSOC) {
      $value = array_values($value);
    }

    $add_more_button_name = $element['#name'] . '[.add_more]';

    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    $module_path = drupal_get_path('module', 'cfrapi');

    $element['#attached']['css'][] = $module_path . '/css/cfrapi.tabledrag.css';
    $element['#attached']['css'][] = $module_path . '/css/cfrapi.sequence-tabledrag.css';
    $element['#attached']['library'][] = ['system', 'jquery.cookie'];
    $element['#attached']['js'][] = 'misc/tabledrag.js';
    $element['#attached']['js'][] = $module_path . '/js/cfrapi.tabledrag.js';

    $element['items'] = [];
    $element['items']['#parents'] = $element['#parents'];

    $element['items']['#pre_render'][] = T::c('_cfrapi_generic_pre_render');
    $element['items']['#cfrapi_pre_render'][] = function (array $itemsElement) {
      return $this->preRenderItems($itemsElement);
    };

    $parents = $element['#parents'];

    foreach ($value as $delta => $itemValue) {

      if (static::ASSOC) {
        if (NULL !== $this->deltaGetValidationError($delta)) {
          // Skip illegal array keys.
          continue;
        }
        // This case cannot happen in case of non-assoc, because we called array_values() above.
        if (0 === strpos($delta, '#')) {
          // Skip keys that break render elements.
          continue;
        }
      }


      $itemConf = isset($conf[$delta]) ? $conf[$delta] : NULL;
      $element['items'][$delta] = [
        # '#type' => 'container',
        '#attributes' => ['class' => ['cfrapi-sequence-item']],
      ];

      $element['items'][$delta]['handle'] = [
        '#markup' => '<!-- -->',
      ];

      $element['items'][$delta]['item'] = [
        '#theme_wrappers' => ['container'],
        '#attributes' => [
          'class' => ['cfrapi-sequence-item-form'],
        ],
      ];

      $itemLabel = $this->sequenceFormula->deltaGetItemLabel(
        $delta,
        $this->translator);

      if (1
        && isset($element['#title'])
        && '' !== ($sequenceLabel = $element['#title'])
      ) {
        $itemLabel = $sequenceLabel . ': ' . $itemLabel;
      }

      $itemForm = $this->itemFormator->confGetD8Form($itemConf, $itemLabel);

      $element['items'][$delta]['item']['form']['conf'] = $itemForm;

      $element['items'][$delta]['item']['form']['conf']['#parents'] = array_merge(
        $element['#parents'],
        [$delta]);

      $element['items'][$delta]['delete'] = [
        '#attributes' => ['class' => ['cfrapi-sequence-delete-container']],
      ];

      $element['items'][$delta]['delete']['button'] = [
        '#type' => 'submit',
        '#parents' => array_merge($element['#parents'], ['.delete', $delta]),
        '#name' => $element['#name'] . '[.delete][' . $delta . ']',
        '#value' => t('Remove'),
        '#attributes' => ['class' => ['cfrapi-sequence-delete']],
        '#submit' => [
          static function (array $element, array &$form_state) use ($parents, $delta) {
            $value = NestedArray::getValue($form_state['input'], $parents);
            if (!\is_array($value)) {
              $value = [];
            }
            unset($value[$delta]);
            if (!static::ASSOC) {
              $value = array_values($value);
            }
            NestedArray::setValue($form_state['input'], $parents, $value);
            $form_state['rebuild'] = TRUE;
          },
        ],
        '#ajax_relative_parents' => ['..', '..', '..'],
        '#ajax' => [
          'callback' => [$this, 'ajaxCallback'],
          'wrapper' => $uniqid,
          'method' => 'replace',
        ],
        '#limit_validation_errors' => [],
      ];
    }

    if (empty($element['items'])) {
      $element['items']['#markup'] = '<!-- -->';
    }

    $element['items']['#prefix'] = '<div id="' . $uniqid . '" class="cfrapi-sequence-items">';
    $element['items']['#suffix'] = '</div>';

    if (!static::ASSOC) {
      $element['.add_more'] = [
        '#type' => 'submit',
        '#name' => $add_more_button_name,
        '#value' => $itemLabelCallback(NULL),
        '#attributes' => ['class' => ['cfrapi-sequence-add-more']],
        '#submit' => [
          static function (array $element, array &$form_state) use ($parents) {
            $value = NestedArray::getValue($form_state['input'], $parents);
            if (!\is_array($value)) {
              $value = [];
            }
            $value[] = null;
            $value = array_values($value);
            NestedArray::setValue($form_state['input'], $parents, $value);
            $form_state['rebuild'] = TRUE;
          },
        ],
        '#ajax_relative_parents' => ['..', 'items'],
        '#ajax' => [
          'callback' => [$this, 'ajaxCallback'],
          'wrapper' => $uniqid,
          'method' => 'replace',
        ],
        '#limit_validation_errors' => [],
      ];
    }
    else {
      $element['items']['.add_more'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['cfrapi-assoc_add_more'],
        ]
      ];

      $element['items']['.add_more']['handle'] = [
        '#markup' => '',
      ];

      $element['items']['.add_more']['key'] = [
        '#name' => $element['#name'] . '[.add_more_key]',
        /* @see \theme_textfield() */
        '#type' => 'textfield',
        '#title' => t('Machine name for new item'),
        '#default_value' => '',
        '#required' => FALSE,
        '#size' => 36,
      ];

      $element_name = $element['#name'];

      $element['items']['.add_more']['add'] = [
        '#type' => 'container',
      ];

      $element['items']['.add_more']['add']['button'] = [
        '#type' => 'submit',
        '#name' => $add_more_button_name,
        '#value' => $itemLabelCallback(NULL),
        '#attributes' => ['class' => ['cfrapi-sequence-add-more']],
        '#validate' => [
          function (array $form, array &$form_state) use ($parents, $element_name) {
            $validation_element_name = implode('][', $parents) . '][.add_more_key';
            $value = NestedArray::getValue($form_state['input'], $parents);
            if (0
              || !isset($form_state['cfrapi-add_more_keys'][$element_name])
              || '' === ($add_more_key = $form_state['cfrapi-add_more_keys'][$element_name])
            ) {
              form_set_error(
                $validation_element_name,
                t('No key specified.'));
              return;
            }
            if (NULL !== $error = $this->deltaGetValidationError($add_more_key)) {
              form_set_error($validation_element_name, $error);
            }
            if (!\is_array($value)) {
              $value = [];
            }
            if (isset($value[$add_more_key])) {
              form_set_error(
                $validation_element_name,
                t(
                  'Key @key already exists.',
                  [
                    '@key' => var_export($add_more_key, TRUE),
                  ]));
              return;
            }
          },
        ],
        '#submit' => [
          static function (array $form, array &$form_state) use ($parents, $element_name) {
            $value = NestedArray::getValue($form_state['input'], $parents);
            $add_more_key = isset($form_state['cfrapi-add_more_keys'][$element_name])
              ? $form_state['cfrapi-add_more_keys'][$element_name]
              : '';
            if (!\is_array($value)) {
              $value = [];
            }
            $value[$add_more_key] = NULL;
            NestedArray::setValue($form_state['input'], $parents, $value);
            $form_state['rebuild'] = TRUE;
          },
        ],
        '#ajax_relative_parents' => ['..', '..', '..'],
        '#ajax' => [
          'callback' => T::c([$this, 'ajaxCallback']),
          'wrapper' => $uniqid,
          'method' => 'replace',
        ],
        '#limit_validation_errors' => [],
      ];
    }

    return $element;
  }

  /**
   * @param array $form
   * @param array $form_state
   *
   * @return array
   */
  public function ajaxCallback(array $form, array &$form_state) {
    $parents = $form_state['triggering_element']['#array_parents'];
    foreach ($form_state['triggering_element']['#ajax_relative_parents'] as $parent) {
      if ('..' === $parent) {
        array_pop($parents);
      }
      else {
        $parents[] = $parent;
      }
    }

    $element = NestedArray::getValue($form, $parents);

    return $element;
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, FormStateInterface $form_state) {

    return;
    // @todo Check if this is still needed in D8.
    foreach (['values', 'input'] as $key) {
      $value = drupal_array_get_nested_value($form_state[$key], $element['#parents']);
      if (!\is_array($value)) {
        $value = [];
      }
      else {
        unset($value['.delete']);
        unset($value['.add_more']);
      }
      drupal_array_set_nested_value($form_state[$key], $element['#parents'], $value);
    }

    return $element;
  }

  /**
   * @param array $itemsElement
   *
   * @return array
   */
  private function preRenderItems(array $itemsElement) {

    $rows = [];
    foreach (element_children($itemsElement) as $delta) {

      if (!static::ASSOC) {
        if (!is_numeric($delta)) {
          continue;
        }
      }
      else {
        if (NULL !== $this->deltaGetValidationError($delta)) {
          // Skip illegal array keys.
          continue;
        }
      }

      $item_element = $itemsElement[$delta];
      $cells = [];
      foreach (element_children($item_element) as $colname) {
        $cell_element = $item_element[$colname];
        $cell = ['data' => drupal_render($cell_element)];
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
      unset($itemsElement[$delta]);
    }

    if (static::ASSOC) {
      if (isset($itemsElement['.add_more'])) {
        $add_more_element = $itemsElement['.add_more'];
        $cells = [];
        foreach (element_children($add_more_element) as $colname) {
          $cell_element = $add_more_element[$colname];
          $cell = ['data' => drupal_render($cell_element)];
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
        unset($itemsElement['.add_more']);
      }
    }

    if ([] === $rows) {
      return $itemsElement;
    }

    $table_element = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#attributes' => $itemsElement['#attributes'],
    ];

    $table_element['#attributes']['class'][] = 'cfrapi-tabledrag';

    $itemsElement['table'] = $table_element;

    return $itemsElement;
  }

  /**
   * @param string $delta
   *
   * @return string|null
   */
  protected function deltaGetValidationError($delta) {
    return NULL;
  }

}
