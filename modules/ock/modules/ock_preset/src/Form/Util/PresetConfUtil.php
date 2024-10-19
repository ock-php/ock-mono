<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Form\Util;

use Drupal\Core\Config\ConfigBase;
use Drupal\Core\Config\ConfigFactoryInterface;

class PresetConfUtil {

  public const CONF_PREFIX = 'ock_preset.preset.';

  /**
   * @param string $interface
   * @param string $preset_name
   *
   * @return string
   */
  public static function presetConfKey(string $interface, string $preset_name): string {
    $key = self::interfaceConfPrefix($interface) . $preset_name;
    ConfigBase::validateName($key);
    return $key;
  }

  /**
   * @param string $interface
   *
   * @return string
   */
  public static function interfaceConfPrefix(string $interface): string {
    return self::CONF_PREFIX
      . str_replace('\\', '-', $interface) . '.';
  }

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *
   * @return \Drupal\Core\Config\ImmutableConfig[][]
   */
  public static function loadAll(ConfigFactoryInterface $configFactory): array {

    $keys = $configFactory->listAll(self::CONF_PREFIX);

    /** @var \Drupal\Core\Config\ImmutableConfig[][] $configss */
    $configss = [];
    foreach ($keys as $key) {
      [,, $interface, $machine_name] = explode('.', $key);
      $configss[$interface][$machine_name] = $configFactory->get($key);
    }

    return $configss;
  }

}
