<?php
declare(strict_types=1);

namespace Drupal\ock\Util;

final class UiUtil extends UtilBase {

  /**
   * @param string $interface
   *
   * @return bool
   */
  public static function interfaceNameIsValid($interface): bool {
    // DRUPAL_PHP_FUNCTION_PATTERN was removed in Drupal 9 without a good
    // alternative, see https://www.drupal.org/project/drupal/issues/3115143.
    $fragment = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
    $backslash = preg_quote('\\', '/');
    $regex = '/^' . $fragment . '(' . $backslash . $fragment . ')*$/';
    return 1 === preg_match($regex, $interface);
  }

  /**
   * @param string $interface
   *
   * @return string
   */
  public static function interfaceGetSlug($interface): string {
    return str_replace('\\', '.', $interface);
  }

}
