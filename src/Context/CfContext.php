<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Context;

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
  public static function create(array $values = []) {
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
  public function paramValueExists(\ReflectionParameter $param) {
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
  public function paramNameHasValue($paramName) {
    return array_key_exists($paramName, $this->values);
  }

  /**
   * {@inheritdoc}
   */
  public function paramNameGetValue($paramName) {
    return $this->values[$paramName] ?? null;
  }

  /**
   * {@inheritdoc}
   */
  public function getMachineName() {
    return $this->machineName
      ?? $this->machineName = md5(serialize($this->values));
  }
}
