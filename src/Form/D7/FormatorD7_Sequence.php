<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;

class FormatorD7_Sequence implements FormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7Interface
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
     * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
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
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ): ?FormatorD7Interface {

    $formator = FormatorD7_SequenceWithEmptiness::createOrNull(
      $schema,
      $schemaToAnything,
      $translator);

    if (NULL !== $formator) {
      return $formator;
    }

    return new FormatorD7_Broken(
      t("Sequences without emptiness are currently not supported."));
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7Interface $itemFormator
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(FormatorD7Interface $itemFormator, TranslatorInterface $translator) {
    $this->itemFormator = $itemFormator;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

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

    $form['#attributes']['class'][] = 'cfrapi-child-options';

    $form += [
      # '#tree' => TRUE,
      '#input' => TRUE,
      '#default_value' => $conf,
      '#_value_callback' => function (array $element, $input, array &$form_state) use ($_this) {
        return $_this->elementValue($element, $input, $form_state);
      },
      '#process' => [
        function (array $element, array &$form_state, array $form) use ($_this) {
          return $_this->elementProcess(
            $element,
            $form_state,
            $form);
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this) {
          return $_this->elementAfterBuild(
            $element,
            $form_state);
        },
      ],
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param mixed|false $input
   * @param array $form_state
   *
   * @return array
   */
  private function elementValue(
    array $element,
    $input,
    /** @noinspection PhpUnusedParameterInspection */ array &$form_state
  ): array {

    if (FALSE === $input) {
      return $element['#default_value'] ?? null;
    }

    return $input;
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param array $form
   *
   * @return array
   */
  private function elementProcess(array $element, array &$form_state, array $form): array {

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

    if (isset($form_state['triggering_element']['#parents'])) {
      $triggering_element_parents = $form_state['triggering_element']['#parents'];
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

      $itemElement = $this->itemFormator->confGetD7Form(
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
              array &$form_state
            ) {
              $button = $form_state['triggering_element'];
              $parents = \array_slice($button['#array_parents'], 0, -1);
              # $delta = end($parents);
              $conf = ConfUtil::confExtractNestedValue(
                $form_state['values'],
                $parents);
              dpm(get_defined_vars(), 'CLOSURE: remove #submit');
              # kdpm($conf, '$conf BEFORE');
              # kdpm($form_state['values'], '$form_state[values] BEFORE');
              ConfUtil::confUnsetNestedValue($form_state['values'], $parents);
              ConfUtil::confUnsetNestedValue($form_state['input'], $parents);
              # kdpm($conf, '$conf AFTER');
              # kdpm($form_state['values'], '$form_state[values] AFTER');
              # kdpm($button, '$button');
              $form_state['rebuild'] = TRUE;
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
            'callback' => function(array $form, array $form_state) use ($itemId) {
              dpm('CLOSURE: remove #ajax callback');

              return [
                '#type' => 'ajax',
                '#commands' => [
                  ajax_command_remove('#' . $itemId),
                ],
              ];
            },
            'callback_' => function(array $form, array $form_state) use ($conf) {

              dpm($conf, 'CONF');
              end($conf);
              $new_item_delta = key($conf);

              $button = $form_state['triggering_element'];

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
        function (array $form, array &$form_state) {
          dpm('CLOSURE: addmore #submit');
          $button = $form_state['triggering_element'];
          $parents = \array_slice($button['#parents'], 0, -1);
          array_pop($parents);
          $conf = ConfUtil::confExtractNestedValue(
            $form_state['values'],
            $parents);
          # kdpm($conf, '$conf BEFORE');
          # kdpm($form_state['values'], '$form_state[values] BEFORE');
          $conf[] = NULL;
          ConfUtil::confSetNestedValue(
            $form_state['values'],
            $parents,
            $conf);
          # kdpm($conf, '$conf AFTER');
          # kdpm($form_state['values'], '$form_state[values] AFTER');
          # kdpm($button, '$button');
          $form_state['rebuild'] = TRUE;
        },
      ],
      '#limit_validation_errors' => [],
      '#ajax' => [
        // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_callback/7.x-1.x
        'callback' => function(array $form, array $form_state) use ($conf) {
          dpm('CLOSURE: addmore #ajax callback');
          dpm($conf, 'CONF');

          end($conf);
          $new_item_delta = key($conf);

          $button = $form_state['triggering_element'];

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
   * @param array $form_state
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    array &$form_state): array {

    $conf = ConfUtil::confExtractNestedValue(
      $form_state['values'],
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
      $form_state['values'],
      $element['#parents'],
      $conf);

    if (isset($element['#title']) && '' !== $element['#title']) {
      $element['#theme_wrappers'][] = 'form_element';
    }

    return $element;
  }
}
