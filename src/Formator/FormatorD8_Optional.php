<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\ConfUtil;
use Donquixote\OCUI\Util\StaUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Optional implements FormatorD8Interface {

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_OptionalInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?FormatorD8Interface {

    if (NULL !== $emptiness = StaUtil::emptinessOrNull(
      $formula->getDecorated(),
      $formulaToAnything)
    ) {
      return FormatorD8::optional(
        $formula->getDecorated(),
        $formulaToAnything
      );
    }

    $decorated = FormatorD8::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything
    );

    return new self($decorated);
  }

  /**
   * @param \Drupal\cu\Formator\FormatorD8Interface $decorated
   */
  public function __construct(FormatorD8Interface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

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
        '#attributes' => ['class' => ['cu-child-options']],
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
            $element['enabled']['#parents']);

          if (empty($enabled)) {
            ConfUtil::confUnsetNestedValue(
              $form_state->getValues(),
              $element['options']['#parents']);
          }

          return $element;
        },
      ],
    ];

    return $form;
  }
}
