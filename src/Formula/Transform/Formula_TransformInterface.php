<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Transform;

use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_TransformInterface extends Formula_DecoratorBaseInterface {

  /**
   * Unpacks and validates stored configuration.
   *
   * @param mixed $packed_conf
   *   Configuration from storage.
   *
   * @return mixed
   *   Configuration compatible with the decorated formula.
   *
   * @throws \Exception
   *   Stored configuration is invalid.
   */
  public function unpack($packed_conf);

  /**
   * Packs configuration for storage.
   *
   * @param mixed $unpacked_conf
   *   Configuration compatible with the decorated formula.
   *
   * @return mixed
   *   Configuration to be stored.
   *
   * @throws \Exception
   *   Unpacked configuration is invalid.
   */
  public function pack($unpacked_conf);

}
