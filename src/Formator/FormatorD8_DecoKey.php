<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\AjaxCallback\AjaxCallback_ElementWithProperty;

class FormatorD8_DecoKey implements FormatorD8Interface {

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface
   */
  private FormatorD8Interface $decorated;

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface
   */
  private FormatorD8Interface $decorator;

  private string $key;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_DecoKeyInterface $formula, IncarnatorInterface $incarnator): ?self {
    return new self(
      FormatorD8::fromFormula(
        $formula->getDecorated(),
        $incarnator),
      FormatorD8::fromFormula(
        new Formula_Sequence_ItemLabelT(
          $formula->getDecoratorFormula(),
          Text::t('New decorator'),
          Text::t('Decorator #!n')),
        $incarnator),
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
    FormatorD8Interface $decorated,
    FormatorD8Interface $decorator,
    string $key
  ) {
    $this->decorated = $decorated;
    $this->decorator = $decorator;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {

    return [
      '#type' => 'themekit_container',
      '#attributes' => ['class' => ['ock-decorator']],
      // Use closures so that the actual methods can remain private.
      '#process' => [function (array $element, FormStateInterface $form_state, array $form) use ($conf, $label) {
        return $this->elementProcess($element, $form_state, $form, $conf, $label);
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
   * Called from a '#process' callback.
   *
   * @param array $element
   *   The form element for this configurator.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param array $form
   *   The complete form.
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   *   Processed element.
   */
  private function elementProcess(array $element, FormStateInterface $form_state, array $form, $conf, $label): array {

    $element['_main'] = $this->decorated->confGetD8Form($conf, $label);
    $element['_main']['#parents'] = $parents = $element['#parents'];

    // @todo Allow to hide the decorators.
    $element['_decorators'] = $this->decorator->confGetD8Form(
      $conf[$this->key] ?? [],
      t('Decorators for @plugin', ['@plugin' => $label]));
    $element['_decorators']['#parents'] = [...$parents, $this->key];

    return $element;
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
