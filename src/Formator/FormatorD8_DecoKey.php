<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Donquixote\Ock\Text\Text;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\UI\AjaxCallback\AjaxCallback_ElementWithProperty;

class FormatorD8_DecoKey implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_DecoKeyInterface $formula, UniversalAdapterInterface $adapter): ?self {
    return new self(
      FormatorD8::fromFormula($formula->getDecorated(), $adapter),
      FormatorD8::fromFormula(
        new Formula_Sequence_ItemLabelT(
          $formula->getDecoratorFormula(),
          Text::t('New decorator'),
          Text::t('Decorator #!n')),
        $adapter),
      $formula->getDecoKey());
  }

  /**
   * Constructor.
   *
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorated
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorator
   * @param string $key
   */
  public function __construct(
    private readonly FormatorD8Interface $decorated,
    private readonly FormatorD8Interface $decorator,
    private readonly string $key
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return [
      '#type' => 'themekit_container',
      '#attributes' => ['class' => ['ock-decorator']],
      // Use closures so that the actual methods can remain private.
      '#process' => [$this->f_elementProcess($conf, $label)],
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

    // Ignore input, get value from nested elements.
    $value = $form_state->getValue($element['#parents']);

    return $value;
  }

  /**
   * Creates a callback for '#process'.
   *
   * @param mixed $conf
   * @param string|null|\Drupal\Component\Render\MarkupInterface $label
   *
   * @return callable(array, FormStateInterface, array): array
   *   Process callback.
   */
  private function f_elementProcess(mixed $conf, string|MarkupInterface|null $label): callable {
    return function (array $element, FormStateInterface $form_state, array $form) use ($conf, $label) {
      $element['_main'] = $this->decorated->confGetD8Form($conf, $label);
      $element['_main']['#parents'] = $parents = $element['#parents'];

      // @todo Allow to hide the decorators.
      $element['_decorators'] = $this->decorator->confGetD8Form(
        $conf[$this->key] ?? [],
        t('Decorators for @plugin', ['@plugin' => $label]));
      $element['_decorators']['#parents'] = [...$parents, $this->key];

      return $element;
    };
  }

  /**
   * @param array $element
   *
   * @return array
   */
  private function ajaxify(array &$element): array {

    $uniqid = sha1(serialize($element['#parents']));

    $element['#prefix'] = '<div id="' . $uniqid . '" class="ock-deco-ajax-wrapper">';
    $element['#suffix'] = '</div>';

    return [
      'callback' => new AjaxCallback_ElementWithProperty('#prefix', $element['#prefix']),
      'wrapper' => $uniqid,
      'method' => 'replace',
    ];
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
    return $element;
  }

}
