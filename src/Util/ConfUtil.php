<?php
declare(strict_types=1);

namespace Donquixote\Cf\Util;

final class ConfUtil extends UtilBase {

  /**
   * @param mixed $conf
   * @param string $k0
   * @param string $k1
   *
   * @return array
   *   Format: [$enabled, $options]
   */
  public static function confGetStatusAndOptions($conf, $k0 = 'enabled', $k1 = 'options'): array {

    if (!\is_array($conf) || empty($conf[$k0])) {
      return [FALSE, NULL];
    }

    if (!isset($conf[$k1])) {
      return [TRUE, NULL];
    }

    return [TRUE, $conf[$k1]];
  }

  /**
   * @param mixed $conf
   *
   * @return string|null
   */
  public static function confGetId($conf): ?string {

    if (is_numeric($conf)) {
      return (string)$conf;
    }

    if (NULL === $conf || '' === $conf || !\is_string($conf)) {
      return NULL;
    }

    return $conf;
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *
   * @return mixed
   */
  public static function confExtractNestedValue(&$conf, array $parents) {
    if ([] === $parents) {
      return $conf;
    }
    if (!\is_array($conf)) {
      return NULL;
    }
    $key = array_shift($parents);
    if (!isset($conf[$key])) {
      return NULL;
    }
    if ([] === $parents) {
      return $conf[$key];
    }
    if (!\is_array($conf[$key])) {
      return NULL;
    }
    return self::confExtractNestedValue($conf[$key], $parents);
  }

  /**
   * @param array $conf
   * @param string[] $parents
   *   Trail of keys indicating an array position within $conf.
   * @param array $value
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public static function confMergeNestedValue(array &$conf, array $parents, array $value): bool {
    if ([] === $parents) {
      $conf += $value;
      return TRUE;
    }
    $key = array_shift($parents);
    if (!isset($conf[$key])) {
      $conf[$key] = [];
    }
    elseif (!\is_array($conf[$key])) {
      return FALSE;
    }
    if ([] === $parents) {
      $conf[$key] += $value;
      return TRUE;
    }
    return self::confMergeNestedValue($conf[$key], $parents, $value);
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *   Trail of keys indicating an array position within $conf.
   * @param mixed $value
   *
   * @return bool
   *   TRUE on success, FALSE on failure.
   */
  public static function confSetNestedValue(&$conf, array $parents, $value): bool {
    if ([] === $parents) {
      $conf = $value;
      return TRUE;
    }
    if (!\is_array($conf)) {
      return FALSE;
    }
    $key = array_shift($parents);
    if ([] === $parents) {
      $conf[$key] = $value;
      return TRUE;
    }
    if (!isset($conf[$key])) {
      $conf[$key] = [];
    }
    return self::confSetNestedValue($conf[$key], $parents, $value);
  }

  /**
   * @param mixed $conf
   * @param string[] $parents
   *
   * @return bool
   */
  public static function confUnsetNestedValue(&$conf, array $parents): bool {
    if ([] === $parents) {
      $conf = [];
      return TRUE;
    }
    if (!\is_array($conf)) {
      return FALSE;
    }
    $key = array_shift($parents);
    if ([] === $parents) {
      unset($conf[$key]);
      return TRUE;
    }
    if (!isset($conf[$key])) {
      return TRUE;
    }
    return self::confUnsetNestedValue($conf[$key], $parents);
  }
}
