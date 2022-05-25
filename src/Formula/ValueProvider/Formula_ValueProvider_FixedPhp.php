<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\ValueProvider;

use Donquixote\Ock\Util\PhpUtil;

class Formula_ValueProvider_FixedPhp implements Formula_ValueProviderInterface {

  /**
   * @param class-string $class
   * @param array $argsPhp
   *
   * @return self
   */
  public static function fromClass(string $class, array $argsPhp = []): self {
    return new self(
      PhpUtil::phpNewClass($class, $argsPhp));
  }

  /**
   * @param callable $method
   * @param array $argsPhp
   *
   * @return self
   */
  public static function fromStaticMethod(callable $method, array $argsPhp = []): self {
    return new self(
      PhpUtil::phpCallStatic($method, $argsPhp));
  }

  /**
   * @param callable-string $function
   * @param array $argsPhp
   *
   * @return self
   */
  public static function fromFunction(string $function, array $argsPhp = []): self {
    return new self(
      PhpUtil::phpCallFunction($function, $argsPhp));
  }

  /**
   * Static factory. Creates an instance from a given value.
   *
   * Throws an "unchecked" exception on failure.
   *
   * @param mixed $value
   *   Simple value that is known to be safe for export.
   *
   * @return self
   */
  public static function fromValueSimple($value): self {
    return new self(PhpUtil::phpValueSimple($value));
  }

  /**
   * Static factory. Creates an instance from a given value.
   *
   * @param mixed $value
   *   Value that is hoped to be safe for export.
   *
   * @return self
   *
   * @throws \Exception
   *   Value is not supported for export.
   */
  public static function fromValue($value): self {
    return new self(PhpUtil::phpValue($value));
  }

  /**
   * Constructor.
   *
   * @param string $php
   *   PHP value expression.
   */
  public function __construct(
    private readonly string $php,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    return $this->php;
  }

}
