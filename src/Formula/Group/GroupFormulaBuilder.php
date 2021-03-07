<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CallbackReflection;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_Callback;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

class GroupFormulaBuilder {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $formulas = [];

  /**
   * @var \Donquixote\OCUI\Text\TextInterface[]
   */
  private $labels = [];

  /**
   * Adds another group option.
   *
   * @param string $key
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *
   * @return $this
   */
  public function add(string $key, FormulaInterface $formula, TextInterface $label): self {
    $this->formulas[$key] = $formula;
    $this->labels[$key] = $label;
    return $this;
  }

  /**
   * @param string $class
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function construct(string $class): FormulaInterface {
    return $this->createWithCallback(
      CallbackReflection::fromClass($class));
  }

  /**
   * @param callable $callback
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function call(callable $callback): FormulaInterface {
    return $this->createWithCallback(
      CallbackReflection::fromCallable($callback));
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function createWithCallback(CallbackReflectionInterface $callbackReflection): FormulaInterface {
    return $this->val(new V2V_Group_Callback($callbackReflection));
  }

  /**
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $v2v
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function val(V2V_GroupInterface $v2v): FormulaInterface {
    return new Formula_GroupVal($this->build(), $v2v);
  }

  /**
   * @return \Donquixote\OCUI\Formula\Group\Formula_GroupInterface
   */
  public function build(): Formula_GroupInterface {
    return new Formula_Group($this->formulas, $this->labels);
  }

}
