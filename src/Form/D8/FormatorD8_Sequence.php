<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Sequence implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface
   */
  private $itemFormator;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return callable
   */
  public static function sta(TranslatorInterface $translator): callable {

    /**
     * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
     * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
     *
     * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
     *
     * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
     */
    return function(
      CfSchema_SequenceInterface $schema,
      SchemaToAnythingInterface $schemaToAnything
    ) use ($translator) {
      return self::create($schema, $schemaToAnything, $translator);
    };
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ): ?FormatorD8Interface {

    $formator = FormatorD8_SequenceWithEmptiness::createOrNull(
      $schema,
      $schemaToAnything,
      $translator);

    if (NULL !== $formator) {
      return $formator;
    }

    return new FormatorD8_Broken(
      t("Sequences without emptiness are currently not supported."));
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8Interface $itemFormator
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(FormatorD8Interface $itemFormator, TranslatorInterface $translator) {
    $this->itemFormator = $itemFormator;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    if ([] === $conf) {
      $conf = [NULL];
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
      # '#tree' => TRUE,
      '#input' => TRUE,
      '#default_value' => $conf,
      '#_value_callback' => function (array $element, $input, FormStateInterface $form_state) use ($_this) {
        return $_this->elementValue($element, $input, $form_state);
      },
      '#process' => [
        function (array $element, FormStateInterface $form_state, array $form) use ($_this) {
          return $_this->elementProcess(
            $element,
            $form_state,
            $form);
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
   * @param mixed|false $input
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementValue(
    array $element,
    $input,
    /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state
  ): array {

    if (FALSE === $input) {
      return $element['#default_value'] ?? null;
    }

    return $input;
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $form
   *
   * @return array
   */
  private function elementProcess(array $element, FormStateInterface $form_state, array $form): array {

    $form_build_id = $form['form_build_id']['#value'];
    $elementId = sha1($form_build_id . serialize($element['#parents']));

    # $element['#attributes']['id'] = $uniqid;

    $conf = $element['#value'];
    # kdpm($element, __METHOD__);

    # $cconf = ConfUtil::confExtractNestedValue($form_state['values'], $element['#parents']);
    # kdpm(get_defined_vars(), __METHOD__);

    if (!\is_array($conf)) {
      $conf = [];
    }

    if (1
      && ($triggering_element =& $form_state->getTriggeringElement())
      && isset($triggering_element['#parents'])
    ) {
      $triggering_element_parents = $triggering_element['#parents'];
      $triggering_element_parents_expected = array_merge($element['#parents'], ['addmore']);
      if ($triggering_element_parents_expected === $triggering_element_parents) {
        // The 'addmore' was clicked. Add another item.
        $conf[] = NULL;
      }
      dpm(implode(' / ', $triggering_element_parents), 'TRIGGERING ELEMENT');
    }

    # $_this = $this;

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      $itemId = $elementId . '-' . $delta;

      $itemElement = $this->itemFormator->confGetD8Form(
        $itemConf,
        $this->deltaGetItemLabel($delta, $this->translator));

      $itemElement['#parents'] = array_merge($element['#parents'], [$delta]);

      $element[$delta] = [
        '#type' => 'container',
        '#attributes' => ['id' => $itemId],
        'item' => $itemElement,
        'remove' => [
          '#name' => implode('-', $element['#parents']) . '-' . $delta . '-remove',
          '#type' => 'submit',
          '#value' =>  t('Remove'),
          '#submit' => [
            // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_add_one/7.x-1.x
            function (
              /** @noinspection PhpUnusedParameterInspection */ array $form,
              FormStateInterface $form_state
            ) {
              if (!$button =& $form_state->getTriggeringElement()) {
                return;
              }
              $parents = \array_slice($button['#array_parents'], 0, -1);
              # $delta = end($parents);
              $conf = ConfUtil::confExtractNestedValue(
                $form_state->getValues(),
                $parents);
              dpm(get_defined_vars(), 'CLOSURE: remove #submit');
              # kdpm($conf, '$conf BEFORE');
              # kdpm($form_state['values'], '$form_state[values] BEFORE');
              ConfUtil::confUnsetNestedValue($form_state->getValues(), $parents);
              ConfUtil::confUnsetNestedValue($form_state->getUserInput(), $parents);
              # kdpm($conf, '$conf AFTER');
              # kdpm($form_state['values'], '$form_state[values] AFTER');
              # kdpm($button, '$button');
              $form_state->setRebuild(TRUE);
            },
          ],
          '#limit_validation_errors' => [$element['#parents']],
          '#ajax' => [
            'wrapper' => $itemId,
            # 'effect' => 'fade',
            # 'method' => 'replace',
            'method' => 'remove',
            'progress' => [
              'type' => 'throbber',
              'message' => NULL,
            ],
            'effect' => 'none',
            // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_callback/7.x-1.x
            'callback' => function(array $form, FormStateInterface $form_state) use ($itemId) {
              dpm('CLOSURE: remove #ajax callback');

              /** @noinspection PhpUndefinedFunctionInspection */
              return [
                '#type' => 'ajax',
                '#commands' => [
                  ajax_command_remove('#' . $itemId),
                ],
              ];
            },
            'callback_' => function(array $form, FormStateInterface $form_state) use ($conf) {

              dpm($conf, 'CONF');
              end($conf);
              $new_item_delta = key($conf);

              $button =& $form_state->getTriggeringElement();

              // Go one level up in the form, to the sequence element.
              $element = ConfUtil::confExtractNestedValue(
                $form,
                \array_slice($button['#array_parents'], 0, -1));

              # kdpm($element);

              # $element['x']['#markup'] = '<div class="ajax-new-content">X</div>';

              # $element

              $element = $element[$new_item_delta];

              return $element;
            },
          ],
        ],
      ];
    }

    # kdpm($element, __METHOD__ . ' FINISHED ELEMENT');

    $addmore = [
      '#parents' => array_merge($element['#parents'], ['addmore']),
      # '#tree' => TRUE,
      '#type' => 'button',
      '#value' =>  t('Add item'),
      '#weight' => 10,
      '#submit' => [
        // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_add_one/7.x-1.x
        function (array $form, FormStateInterface $form_state) {
          dpm('CLOSURE: addmore #submit');
          if (NULL === $button =& $form_state->getTriggeringElement()) {
            return;
          }
          $parents = \array_slice($button['#parents'], 0, -1);
          array_pop($parents);
          $conf = ConfUtil::confExtractNestedValue(
            $form_state->getValues(),
            $parents);
          # kdpm($conf, '$conf BEFORE');
          # kdpm($form_state['values'], '$form_state[values] BEFORE');
          $conf[] = NULL;
          ConfUtil::confSetNestedValue(
            $form_state->getValues(),
            $parents,
            $conf);
          # kdpm($conf, '$conf AFTER');
          # kdpm($form_state['values'], '$form_state[values] AFTER');
          # kdpm($button, '$button');
          $form_state->setRebuild(TRUE);
        },
      ],
      '#limit_validation_errors' => [],
      '#ajax' => [
        // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_callback/7.x-1.x
        'callback' => function(array $form, FormStateInterface $form_state) use ($conf) {
          dpm('CLOSURE: addmore #ajax callback');
          dpm($conf, 'CONF');

          end($conf);
          $new_item_delta = key($conf);

          $button =& $form_state->getTriggeringElement();

          // Go one level up in the form, to the sequence element.
          $element = ConfUtil::confExtractNestedValue(
            $form,
            \array_slice($button['#array_parents'], 0, -1));

          # kdpm($element);

          # $element['x']['#markup'] = '<div class="ajax-new-content">X</div>';

          # $element

          $element = $element[$new_item_delta];

          return $element;
        },
        'wrapper' => $elementId,
        # 'effect' => 'fade',
        # 'method' => 'replace',
        'method' => 'before',
      ],
    ];

    $element['replaceme'] = [
      '#weight' => 9,
      # 'addmore' => $addmore,
      '#markup' => '<div id="' . $elementId . '"></div>',
    ];

    $element['addmore'] = $addmore;

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, TranslatorInterface $helper): string {
    return $this->schema->deltaGetItemLabel($delta, $helper);

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

    # $itemSchema = $this->schema->getItemSchema();

    $enabled = false;
    foreach ($conf as $delta => $itemConf) {
      # list($enabled) = $helper->schemaConfGetStatusAndOptions($itemSchema, $itemConf);
      if (!$enabled) {
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
