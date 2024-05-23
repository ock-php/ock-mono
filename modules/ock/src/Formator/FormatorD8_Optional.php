<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Formula\Optional\Formula_OptionalInterface;
use Ock\Ock\Util\ConfUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;

class FormatorD8_Optional implements FormatorD8Interface {

  /**
   * @param \Ock\Ock\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_OptionalInterface $formula,
    UniversalAdapterInterface $adapter
  ): ?FormatorD8Interface {
    return $adapter->adapt(
      $formula->getDecorated(),
      OptionableFormatorD8Interface::class,
    )?->getOptionalFormator();
  }

  /**
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorated
   */
  public function __construct(
    private readonly FormatorD8Interface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $form = [
      '#tree' => TRUE,
      'enabled' => [
        '#title' => $label,
        '#type' => 'checkbox',
        '#default_value' => !empty($conf['enabled']),
      ],
      'options' => [
        '#type' => 'container',
        # '#tree' => TRUE,
        '#attributes' => ['class' => ['ock-child-options']],
        'content' => $this->decorated->confGetD8Form($conf, NULL),
        '#process' => [
          function(array $element) {
            if (isset($element['content'])) {
              $element['content']['#parents'] = $element['#parents'];
            }
            return $element;
          },
        ],
      ],
      '#after_build' => [
        function(array $element /*, array &$form_state */) {
          $element['options']['#states']['visible'] = [
            ':input[' . $element['enabled']['#name'] . ']' => ['checked' => TRUE],
          ];
          return $element;
        },
        // Clear out $conf['options'], if $conf['enabled'] is empty.
        function(array $element, FormStateInterface $form_state) {
          $enabled = ConfUtil::confExtractNestedValue(
            $form_state->getValues(),
            $element['enabled']['#parents'],
          );
          if (empty($enabled)) {
            ConfUtil::confUnsetNestedValue(
              $form_state->getValues(),
              $element['options']['#parents'],
            );
          }
          return $element;
        },
      ],
    ];

    return $form;
  }

}
