<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Drupal\ock_preset\Crud\PresetRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CfSchema_PresetProxy implements CfSchema_Proxy_DrupalContainerInterface {

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
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function containerGetSchema(ContainerInterface $container): CfSchemaInterface {

    /** @var \Drupal\Core\Config\ConfigFactoryInterface $configFactory */
    $configFactory = $container->get('config.factory');

    $repo = new PresetRepository($configFactory);

    $config = $repo->load(
      $this->interface,
      $this->presetMachineName);

    return new CfSchema_PresetConf(
      $this->interface,
      $this->presetMachineName,
      $config->get('conf'));
  }

}
