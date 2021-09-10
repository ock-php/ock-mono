<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal;
use Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\V2V\Group\V2V_Group_Call;
use Donquixote\ObCK\V2V\Group\V2V_Group_Callback;
use Donquixote\ObCK\V2V\Group\V2V_Group_Fixed;
use Donquixote\ObCK\V2V\Group\V2V_Group_ObjectMethodCall;
use Donquixote\ObCK\V2V\Group\V2V_Group_Partials;
use Donquixote\ObCK\V2V\Group\V2V_Group_Rekey;
use Donquixote\ObCK\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

class GroupFormulaBuilder {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private array $formulas = [];

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $labels = [];

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface[]
   */
  private array $partials = [];

  /**
   * Adds another group option.
   *
   * @param string $key
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Text\TextInterface $label
   *
   * @return $this
   */
  public function add(string $key, FormulaInterface $formula, TextInterface $label): self {
    $this->formulas[$key] = $formula;
    $this->labels[$key] = $label;
    return $this;
  }

  /**
   * Adds an optionless group option.
   *
   * @param string $key
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return $this
   */
  public function addOptionless(string $key, Formula_OptionlessInterface $formula): self {
    // @todo Option to not add a label for optionless options?
    return $this->add($key, $formula, Text::s($key));
  }

  /**
   * Adds an optionless group option.
   *
   * @param string $key
   * @param mixed $value
   *
   * @return $this
   */
  public function addValue(string $key, $value): self {
    return $this->addDependentVal(
      $key,
      new V2V_Group_Fixed(PhpUtil::phpValue($value)));
  }

  /**
   * @param string $key
   * @param string $php
   *
   * @return $this
   */
  public function addValuePhp(string $key, string $php): self {
    return $this->addDependentVal(
      $key,
      new V2V_Group_Fixed($php));
  }

  /**
   * @param string $key
   * @param callable $callback
   *
   * @return $this
   */
  public function addValueCall(string $key, callable $callback): self {
    return $this->addDependentVal(
      $key,
      new V2V_Group_Fixed(PhpUtil::phpCall($callback, [])));
  }

  /**
   * @param string $key
   * @param callable $callback
   * @param array $keys
   *
   * @return $this
   */
  public function addDependentCall(string $key, callable $callback, array $keys): self {
    return $this->addDependentVal(
      $key,
      V2V_Group_Call::fromCallable($callback), $keys);
  }

  /**
   * @param string $key
   * @param string $objectKey
   * @param string $method
   * @param array $paramKeys
   *
   * @return $this
   */
  public function addObjectMethodCall(string $key, string $objectKey, string $method, array $paramKeys): self {
    return $this->addDependentVal(
      $key,
      new V2V_Group_ObjectMethodCall($objectKey, $method, $paramKeys));
  }

  /**
   * @param string $key
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   * @param array|null $keys
   *
   * @return $this
   */
  public function addDependentVal(string $key, V2V_GroupInterface $v2v, array $keys = NULL): self {
    if ($keys !== NULL) {
      $v2v = new V2V_Group_Rekey($v2v, $keys);
    }
    $this->partials[$key] = $v2v;
    return $this;
  }

  /**
   * Constructs a class with group options as parameters.
   *
   * @param string $class
   * @param string[]|null $keys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function construct(string $class, array $keys = NULL): FormulaInterface {
    return $this->val(V2V_Group_Call::fromClass($class), $keys);
  }

  /**
   * Endpoint. Calls a factory callback with the group options as parameters.
   *
   * @param callable $callback
   * @param string[]|null $keys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function call(callable $callback, array $keys = NULL): FormulaInterface {
    return $this->val(V2V_Group_Call::fromCallable($callback), $keys);
  }

  /**
   * Endpoint. Calls an object method.
   *
   * @param string $objectKey
   * @param string $method
   * @param array $paramKeys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function callObjectMethod(string $objectKey, string $method, array $paramKeys): FormulaInterface {
    return $this->val(new V2V_Group_ObjectMethodCall($objectKey, $method, $paramKeys));
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param string[]|null $keys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function createWithCallback(CallbackReflectionInterface $callbackReflection, array $keys = NULL): FormulaInterface {
    return $this->val(new V2V_Group_Callback($callbackReflection), $keys);
  }

  /**
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   * @param string[]|null $keys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function val(V2V_GroupInterface $v2v, array $keys = NULL): FormulaInterface {
    return $this->build($v2v, $keys);
  }

  /**
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface|null $v2v
   * @param string[]|null $keys
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function build(V2V_GroupInterface $v2v = NULL, array $keys = NULL): FormulaInterface {
    $formula = new Formula_Group($this->formulas, $this->labels);
    if ($this->partials) {
      $v2v = new V2V_Group_Partials(
        $v2v ?? new V2V_Group_Trivial(),
        $this->partials);
    }
    if ($keys) {
      $v2v = new V2V_Group_Rekey(
        $v2v ?? new V2V_Group_Trivial(),
        $keys);
    }
    if ($v2v) {
      $formula = new Formula_GroupVal($formula, $v2v);
    }
    return $formula;
  }

}
