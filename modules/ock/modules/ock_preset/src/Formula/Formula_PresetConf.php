<?php

declare(strict_types=1);

namespace Drupal\ock_preset\Formula;

use Drupal\ock\Formator\FormatorD8Interface;
use Drupal\ock_preset\Controller\Controller_Preset;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\FixedConf\Formula_FixedConfInterface;
use Ock\Ock\Formula\Formula;

class Formula_PresetConf implements FormatorD8Interface, Formula_FixedConfInterface {

  /**
   * Constructor.
   *
   * @param string $interface
   * @param string $presetMachineName
   * @param mixed $conf
   */
  public function __construct(
    private readonly string $interface,
    private readonly string $presetMachineName,
    private readonly mixed $conf,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {
    $link = [
      '#type' => 'link',
      '#title' => t('edit preset'),
      '#url' => Controller_Preset::route(
        $this->interface,
        $this->presetMachineName)
        ->url(),
      '#attributes' => ['target' => '_blank'],
    ];
    return [
      '#type' => 'container',
      'link' => $link,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return Formula::iface($this->interface);
  }

  /**
   * {@inheritdoc}
   */
  public function getConf(): mixed {
    return $this->conf;
  }

}
