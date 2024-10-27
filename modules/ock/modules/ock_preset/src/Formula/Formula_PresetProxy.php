<?php

declare(strict_types=1);

namespace Drupal\ock_preset\Formula;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\ock_preset\Crud\PresetRepository;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\ServiceProxy\Formula_ContainerProxyInterface;
use Psr\Container\ContainerInterface;

class Formula_PresetProxy implements Formula_ContainerProxyInterface {

  /**
   * Constructor.
   *
   * @param class-string $interface
   * @param string $presetMachineName
   */
  public function __construct(
    private readonly string $interface,
    private readonly string $presetMachineName,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function containerGetFormula(ContainerInterface $container): FormulaInterface {

    /** @var \Drupal\Core\Config\ConfigFactoryInterface $configFactory */
    $configFactory = $container->get(ConfigFactoryInterface::class);

    $repo = new PresetRepository($configFactory);

    $config = $repo->load(
      $this->interface,
      $this->presetMachineName,
    );

    return new Formula_PresetConf(
      $this->interface,
      $this->presetMachineName,
      $config->get('conf'),
    );
  }

}
