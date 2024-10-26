<?php

declare(strict_types=1);

namespace Drupal\ock_preset\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\FormatorD8Interface;
use Drupal\ock_preset\Controller\Controller_Preset;
use Ock\Ock\Formula\ValueProvider\Formula_FixedPhp_Null;

class Formula_PresetLink extends Formula_FixedPhp_Null implements FormatorD8Interface {

  /**
   * Constructor.
   *
   * @param string $interface
   * @param string $presetMachineName
   */
  public function __construct(
    private readonly string $interface,
    private readonly string $presetMachineName,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    $link = [
      '#type' => 'link',
      '#title' => t('edit preset'),
      '#url' => Controller_Preset::route(
        $this->interface,
        $this->presetMachineName,
      )->url(),
      '#attributes' => ['target' => '_blank'],
    ];
    return [
      '#type' => 'container',
      'link' => $link,
    ];
  }

}
