<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\StaUtil;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_Optional implements FormatorD8Interface {

  /**
   * @var \Donquixote\Cf\Form\D8\FormatorD8Interface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD8Interface {

    if (NULL !== $emptiness = StaUtil::emptinessOrNull(
      $schema->getDecorated(),
      $schemaToAnything)
    ) {
      return FormatorD8::optional(
        $schema->getDecorated(),
        $schemaToAnything
      );
    }

    $decorated = FormatorD8::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything
    );

    return new self($decorated);
  }

  /**
   * @param \Donquixote\Cf\Form\D8\FormatorD8Interface $decorated
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
        '#attributes' => ['class' => ['faktoria-child-options']],
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
