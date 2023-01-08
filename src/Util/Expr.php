<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Util;

use Donquixote\CodegenTools\Exception\EvaluationException;

class Expr {

  /**
   * @param int|string $key
   *
   * @return never
   *
   * @throws \Donquixote\CodegenTools\Exception\EvaluationException
   */
  public static function pl(int|string $key): never {
    throw new EvaluationException(sprintf("Unresolved placeholder for '%s'.", $key));
  }

  /**
   * @return never
   */
  public static function plDecorated(): never {
    throw new \RuntimeException('This method is not meant to be called.');
  }

  /**
   * @return never
   */
  public static function plAdaptee(): never {
    throw new \RuntimeException('This method is not meant to be called.');
  }

}
