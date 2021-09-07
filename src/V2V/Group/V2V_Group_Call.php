<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

use Donquixote\ObCK\Util\PhpUtil;

class V2V_Group_Call implements V2V_GroupInterface {

  /**
   * Function name, or static method with '::'.
   *
   * @var string
   */
  private string $fqn;

  /**
   * @param string $class
   *
   * @return static
   */
  public static function fromClass(string $class): self {
    return new self('new \\' . $class);
  }

  /**
   * @param callable $method
   *
   * @return self
   */
  public static function fromStaticMethod(callable $method): self {
    if (!is_array($method) || !is_string($method[0]) || !isset($method[1])) {
      throw new \InvalidArgumentException('Must be a static method.');
    }
    return new self('\\' . $method[0] . '::' . $method[1]);
  }

  /**
   * @param callable $function
   *
   * @return self
   */
  public static function fromFunction(callable $function): self {
    if (!is_string($function)) {
      throw new \InvalidArgumentException('Must be a string function name.');
    }
    return new self('\\' . $function);
  }

  /**
   * @param callable $callable
   *
   * @return self
   */
  public static function fromCallable(callable $callable): self {
    if (is_array($callable)) {
      if (!is_string($callable[0]) || !isset($callable[1])) {
        throw new \InvalidArgumentException('Method must be static.');
      }
      return new self('\\' . $callable[0] . '::' . $callable[1]);
    }
    if (is_string($callable)) {
      return new self('\\' . $callable);
    }
    throw new \InvalidArgumentException('Callable must be a static method or a global function.');
  }

  /**
   * @param string $objectPhp
   * @param string $method
   *
   * @return static
   */
  public static function fromObjectMethod(string $objectPhp, string $method): self {
    return new self($objectPhp . '->' . $method);
  }

  /**
   * Constructor.
   *
   * @param string $fqn
   */
  private function __construct(string $fqn) {
    $this->fqn = $fqn;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return PhpUtil::phpCallFqn($this->fqn, $itemsPhp);
  }

}
