<?php
declare(strict_types=1);

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

final class DrilldownUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param string $id
   * @param mixed $options
   *
   * @return array
   */
  public static function schemaBuildConf(CfSchema_DrilldownInterface $schema, string $id, $options): array {

    return self::buildConf(
      $id,
      $options,
      $schema->getIdKey(),
      $schema->getOptionsKey());
  }

  /**
   * @param string $id
   * @param mixed $options
   * @param string|null $k0
   * @param string|null $k1
   *
   * @return array|mixed
   */
  public static function buildConf(string $id, $options, $k0 = 'id', $k1 = 'options') {

    if (NULL === $k0) {
      return $id;
    }

    if (NULL === $k1) {
      $conf = \is_array($options)
        ? $options
        : [];
      $conf[$k0] = $id;
      return $conf;
    }

    if (NULL === $id) {
      $options = NULL;
    }

    return [
      $k0 => $id,
      $k1 => $options,
    ];
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param mixed $conf
   *
   * @return array
   */
  public static function drilldownConfGetIdOptions(CfSchema_DrilldownInterface $schema, $conf): array {
    return self::confGetIdOptions(
      $conf,
      $schema->getIdKey(),
      $schema->getOptionsKey()
    );
  }

  /**
   * @param mixed $conf
   * @param string|null $k0
   * @param string|null $k1
   *
   * @return array
   *   Format: array($id, $options)
   */
  public static function confGetIdOptions($conf, $k0 = 'id', $k1 = 'options'): array {

    if (NULL === $k0) {
      if (!\is_string($conf) && !\is_int($conf)) {
        return [NULL, NULL];
      }

      return [$conf, NULL];
    }

    if (!\is_array($conf)) {
      return [NULL, NULL];
    }

    if (!isset($conf[$k0])) {
      return [NULL, NULL];
    }

    if ('' === $id = $conf[$k0]) {
      return [NULL, NULL];
    }

    if (!\is_string($id) && !\is_int($id)) {
      return [NULL, NULL];
    }

    if (NULL === $k1) {
      unset($conf[$k0]);
      return [$id, $conf];
    }

    if (!isset($conf[$k1])) {
      return [$id, NULL];
    }

    return [$id, $conf[$k1]];
  }
}
