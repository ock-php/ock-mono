<?php

declare(strict_types=1);

namespace Donquixote\Ock\Context;

class CfContext implements CfContextInterface {

  /**
   * @var mixed[]
   */
  private $values;

  /**
   * @var string|null
   */
  private $machineName;

  /**
   * @param mixed[] $values
   *
   * @return static
   */
  public static function create(array $values = []): self {
    return new static($values);
  }

  /**
   * @param mixed[] $values
   */
  public function __construct(array $values = []) {
    $this->values = $values;
  }

  /**
   * @param string $paramName
   * @param mixed $value
   *
   * @return $this
   */
  public function paramNameSetValue(string $paramName, $value): self {
    $this->values[$paramName] = $value;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function paramValueExists(\ReflectionParameter $param): bool {
    if ($typeHintReflClass = $param->getClass()) {
      if ($typeHintReflClass->getName() === CfContextInterface::class) {
        return TRUE;
      }
    }
    return $this->paramNameHasValue($param->getName());
  }

  /**
   * {@inheritdoc}
   */
  public function paramGetValue(\ReflectionParameter $param) {
    if ($typeHintReflClass = $param->getClass()) {
      if ($typeHintReflClass->getName() === CfContextInterface::class) {
        return $this;
      }
    }
    return $this->paramNameGetValue($param->getName());
  }

  /**
   * {@inheritdoc}
   */
  public function paramNameHasValue($paramName): bool {
    return array_key_exists($paramName, $this->values);
  }

  /**
   * {@inheritdoc}
   */
  public function paramNameGetValue($paramName) {
    return $this->values[$paramName] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getMachineName(): string {
    return $this->machineName
      ??= md5(serialize($this->values));
  }

}
