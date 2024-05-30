<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock_preset\Controller\Controller_IfacePresets;
use Drupal\ock_preset\Controller\Controller_Preset;
use Drupal\ock_preset\Crud\PresetRepository;

class Form_PresetDelete extends ConfirmFormBase {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var string
   */
  private $presetMachineName;

  /**
   * @var string
   */
  private $label;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param string $interface
   * @param string $preset_name
   * @param string $label
   * @param mixed $conf
   */
  public function __construct($interface, $preset_name, $label, $conf) {
    $this->interface = $interface;
    $this->presetMachineName = $preset_name;
    $this->label = $label;
    $this->conf = $conf;
  }

  /**
   * Returns the question to ask the user.
   *
   * @return string
   *   The form question. The page title will be set to this value.
   */
  public function getQuestion() {
    return $this->t(
      'Delete the preset %label?',
      ['%label' => $this->label]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * @return \Drupal\Core\Url
   */
  public function getCancelUrl() {
    return Controller_Preset::route(
      $this->interface,
      $this->presetMachineName)
      ->url();
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'ock_preset_preset_delete_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $config = PresetRepository::create()->load(
      $this->interface,
      $this->presetMachineName);

    if ($config->isNew()) {
      $form_state->setError(
        $form,
        $this->t('The preset does no longer exist.'));
      return;
    }

    if ($config->get('conf') !== $this->conf) {
      $form_state->setError(
        $form,
        $this->t('The preset configuration was modified since this form was built.'));
    }

    if ($config->get('label') !== $this->label) {
      $form_state->setError(
        $form,
        $this->t('The preset label was modified since this form was built.'));
    }
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = PresetRepository::create()->loadEditable(
      $this->interface,
      $this->presetMachineName);

    $config->delete();

    \Drupal::messenger()->addMessage($this->t(
      'The preset %label has been deleted.',
      [
        '%label' => $this->label,
      ]
    ));

    $redirectUrl = Controller_IfacePresets::route($this->interface)
      ->url();

    // Redirect.
    $form_state->setRedirectUrl($redirectUrl);
  }

}
