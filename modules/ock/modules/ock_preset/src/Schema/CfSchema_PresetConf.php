<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\Schema\FixedConf\CfSchema_FixedConfInterface;
use Drupal\ock_preset\Controller\Controller_Preset;

class CfSchema_PresetConf implements FormatorD8Interface, CfSchema_FixedConfInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var string
   */
  private $presetMachineName;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param string $interface
   * @param string $presetMachineName
   * @param mixed $conf
   */
  public function __construct($interface, $presetMachineName, $conf) {
    $this->interface = $interface;
    $this->presetMachineName = $presetMachineName;
    $this->conf = $conf;
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

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface {
    return CfSchema::iface($this->interface);
  }

  /**
   * @return mixed
   */
  public function getConf(): mixed {
    return $this->conf;
  }

}
