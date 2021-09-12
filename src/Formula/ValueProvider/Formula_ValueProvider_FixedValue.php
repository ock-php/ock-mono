<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\ValueProvider;

use Donquixote\Ock\Util\PhpUtil;

class Formula_ValueProvider_FixedValue implements Formula_ValueProviderInterface {

  /**
   * @var string
   */
  private string $php;

  /**
   * @param string $class
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
   * @param string|callable $function
   * @param array $argsPhp
   *
   * @return self
   */
  public static function fromFunction(string $function, array $argsPhp = []): self {
    return new self(
      PhpUtil::phpCallFunction($function, $argsPhp));
  }

  /**
   * @param mixed $value
   *
   * @return self
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
  public function __construct(string $php) {
    $this->php = $php;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    return $this->php;
  }
}
