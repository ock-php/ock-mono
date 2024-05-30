<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock_preset\Controller\Controller_IfacePresets;
use Drupal\ock_preset\Controller\Controller_Preset;
use Drupal\ock_preset\Crud\PresetRepository;

class Form_PresetEdit implements FormInterface {

  /**
   * @var null|string
   */
  private ?string $presetMachineName;

  /**
   * @var null|string
   */
  private ?string $label;

  /**
   * @var mixed|null
   */
  private mixed $conf;

  /**
   * @param string $interface
   *
   * @return self
   */
  public static function create(string $interface): self {
    return new self($interface);
  }

  /**
   * @param string $interface
   */
  public function __construct(
    private readonly string $interface,
  ) {}

  /**
   * @param mixed $conf
   *
   * @return static
   */
  public function withConf(mixed $conf): static {
    $clone = clone $this;
    $clone->conf = $conf;
    return $clone;
  }

  /**
   * @param string $preset_name
   * @param string $label
   * @param mixed $conf
   *
   * @return static
   */
  public function withExistingPreset(string $preset_name, string $label, mixed $conf): static {
    $clone = clone $this;
    $clone->presetMachineName = $preset_name;
    $clone->label = $label;
    $clone->conf = $conf;
    return $clone;
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId(): string {
    return 'ock_preset_edit_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['label'] = [
      '#title' => t('Administrative title'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $this->label,
    ];

    // Placeholder.
    $form['machine_name'] = [];

    $form['conf'] = [
      /* @see \Drupal\ock_preset\Element\FormElement_CfrPlugin */
      '#type' => 'ock_preset',
      '#ock_preset_interface' => $this->interface,
      '#title' => t('Plugin'),
      '#default_value' => $this->conf,
    ];

    $form['actions'] = ['#type' => 'actions'];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save preset'),
      # '#weight' => 5,
      '#button_type' => 'primary',
    ];

    // Placeholder.
    $form['actions']['delete'] = [];

    if (NULL !== $this->presetMachineName) {

      $form['machine_name'] = [
        '#type' => 'value',
        '#value' => $this->presetMachineName,
      ];

      $form['actions']['delete'] = [
        '#type' => 'link',
        '#title' => t('Delete'),
        # '#weight' => 15,
        /* @see _cfrpreset_preset_form_delete_submit() */
        # '#submit' => ['_cfrpreset_preset_form_delete_submit'],
        '#attributes' => [
          'class' => ['button', 'button--danger'],
        ],
        '#button_type' => 'danger',
        '#url' => Controller_Preset::route($this->interface, $this->presetMachineName)
          ->subpage('delete')
          ->url(),
      ];
    }
    else {

      $form['machine_name'] = [
        '#title' => t('Machine name'),
        /* @see form_process_machine_name() */
        /* @see form_validate_machine_name() */
        '#type' => 'machine_name',
        '#machine_name' => [
          'source' => ['label'],
          /* @see presetNameExists() */
          'exists' => [$this, 'presetNameExists'],
        ],
        '#required' => TRUE,
      ];
    }

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // Nothing.
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    /** @noinspection ReferenceMismatchInspection */
    $values = $form_state->getValues();

    if (NULL === $this->presetMachineName) {
      // This is a new preset.
      $preset_name = $values['machine_name'];
      $message = 'Preset %name was successfully created.';
    }
    else {
      $preset_name = $this->presetMachineName;
      $message = 'Preset %name was successfully updated.';
    }

    $config = PresetRepository::create()
      ->loadEditable($this->interface, $preset_name);

    $config->set('label', $values['label']);
    $config->set('conf', $values['conf']);

    $config->save();

    \Drupal::messenger()->addMessage(
      t($message, ['%name' => $preset_name]));

    $redirectUrl = Controller_IfacePresets::route($this->interface)->url();

    $form_state->setRedirectUrl($redirectUrl);
  }

  /**
   * @param string $preset_name
   *
   * @return bool
   */
  public function presetNameExists(string $preset_name): bool {
    return !PresetRepository::create()
      ->load($this->interface, $preset_name)
      ->isNew();
  }

}
