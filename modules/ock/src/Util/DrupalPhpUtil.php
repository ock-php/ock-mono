<?php
declare(strict_types=1);

namespace Drupal\ock\Util;

use Ock\CodegenTools\Util\CodeGen;

final class DrupalPhpUtil extends UtilBase {

  public static function service(string $id): string {
    // @todo Container should be injected as a variable.
    return CodeGen::phpCallStatic(
      [\Drupal::class, 'service'],
      [var_export($id, TRUE)]);
  }

}
