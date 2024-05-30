<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Crud;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\ock_preset\Form\Util\PresetConfUtil;

class PresetRepository {

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * @return \Drupal\ock_preset\Crud\PresetRepository
   */
  public static function create(): self {
    return new self(\Drupal::configFactory());
  }

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * @return \Drupal\Core\Config\ImmutableConfig[][]
   */
  public function loadAll(): array {

    $keys = $this->configFactory->listAll(PresetConfUtil::CONF_PREFIX);

    /** @var \Drupal\Core\Config\ImmutableConfig[][] $configss */
    $configss = [];
    foreach ($keys as $key) {
      [,, $interface_conf_slug, $machine_name] = explode('.', $key);
      $interface = str_replace('-', '\\', $interface_conf_slug);
      $configss[$interface][$machine_name] = $this->configFactory->get($key);
    }

    return $configss;
  }

  /**
   * @param string $interface
   *
   * @return \Drupal\Core\Config\ImmutableConfig[]
   */
  public function loadForInterface($interface): array {

    $prefix = PresetConfUtil::interfaceConfPrefix($interface);

    $keys = $this->configFactory->listAll($prefix);

    /** @var \Drupal\Core\Config\ImmutableConfig[] $configs */
    $configs = [];
    foreach ($keys as $key) {
      [,,, $machine_name] = explode('.', $key);
      $configs[$machine_name] = $this->configFactory->get($key);
    }

    return $configs;
  }

  /**
   * @param string $interface
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   */
  public function load($interface, $preset_name): ImmutableConfig {

    $key = PresetConfUtil::presetConfKey($interface, $preset_name);

    return $this->configFactory->get($key);
  }

  /**
   * @param string $interface
   * @param string $preset_name
   *
   * @return \Drupal\Core\Config\Config
   */
  public function loadEditable($interface, $preset_name) {

    $key = PresetConfUtil::presetConfKey($interface, $preset_name);

    return $this->configFactory->getEditable($key);
  }

}
