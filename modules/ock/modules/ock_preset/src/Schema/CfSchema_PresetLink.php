<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Schema;

use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Null;
use Drupal\ock_preset\Controller\Controller_Preset;

class CfSchema_PresetLink extends CfSchema_ValueProvider_Null implements FormatorD8Interface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var string
   */
  private $presetMachineName;

  /**
   * @param string $interface
   * @param string $presetMachineName
   */
  public function __construct($interface, $presetMachineName) {
    $this->interface = $interface;
    $this->presetMachineName = $presetMachineName;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
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

}
