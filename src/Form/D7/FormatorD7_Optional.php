<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\StaUtil;

class FormatorD7_Optional implements FormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\FormatorD7Interface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD7Interface {

    if (NULL !== $emptiness = StaUtil::emptinessOrNull(
      $schema->getDecorated(),
      $schemaToAnything)
    ) {
      return D7FormSTAUtil::formatorOptional(
        $schema->getDecorated(),
        $schemaToAnything
      );
    }

    $decorated = D7FormSTAUtil::formator(
      $schema->getDecorated(),
      $schemaToAnything
    );

    return new self($decorated);
  }

  /**
   * @param \Donquixote\Cf\Form\D7\FormatorD7Interface $decorated
   */
  public function __construct(FormatorD7Interface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

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
        '#attributes' => ['class' => ['cfrapi-child-options']],
        'content' => $this->decorated->confGetD7Form($conf, NULL),
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
        function(array $element, array &$form_state) {

          $enabled = ConfUtil::confExtractNestedValue(
            $form_state['values'],
            $element['enabled']['#parents']);

          if (empty($enabled)) {
            ConfUtil::confUnsetNestedValue(
              $form_state['values'],
              $element['options']['#parents']);
          }

          return $element;
        },
      ],
    ];

    return $form;
  }
}
