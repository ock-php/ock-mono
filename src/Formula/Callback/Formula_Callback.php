<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;

class Formula_Callback implements Formula_CallbackInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $explicitFormulas = [];

  /**
   * @var string[]
   */
  private $explicitLabels = [];

  /**
   * @param string $class
   *
   * @return \Donquixote\OCUI\Formula\Callback\Formula_Callback
   */
  public static function fromClass(string $class): Formula_Callback {

    return new self(
      CallbackReflection_ClassConstruction::create($class));
  }

  /**
   * @param string $class
   * @param string $methodName
   *
   * @return self
   */
  public static function fromStaticMethod(string $class, string $methodName): Formula_Callback {

    return new self(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName));
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    CallbackReflectionInterface $callbackReflection,
    CfContextInterface $context = NULL
  ) {
    $this->callbackReflection = $callbackReflection;
  }

  /**
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   *
   * @return static
   */
  public function withContext(CfContextInterface $context = NULL) {
    $clone = clone $this;
    return $clone;
  }

  /**
   * @param int $index
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string|null $label
   *
   * @return static
   */
  public function withParamFormula(int $index, FormulaInterface $formula, $label = NULL) {
    $clone = clone $this;
    $clone->explicitFormulas[$index] = $formula;
    if (NULL !== $label) {
      $clone->explicitLabels[$index] = $label;
    }
    return $clone;
  }

  /**
   * @param int $index
   * @param string $label
   *
   * @return static
   */
  public function withParamLabel(int $index, string $label) {
    $clone = clone $this;
    $clone->explicitLabels[$index] = $label;
    return $clone;
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_Iface(int $index, string $interface, $label = NULL) {
    return $this->withParamFormula(
      $index,
      Formula::iface($interface),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceSequence(int $index, string $interface, $label = NULL) {
    return $this->withParamFormula(
      $index,
      Formula::ifaceSequence($interface),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceOrNull(int $index, string $interface, $label = NULL) {
    return $this->withParamFormula(
      $index,
      Formula::ifaceOrNull($interface),
      $label);
  }

  /**
   * {@inheritdoc}
   */
  public function getCallback(): CallbackReflectionInterface {
    return $this->callbackReflection;
  }

  /**
   * {@inheritdoc}
   */
  public function getExplicitParamFormulas(): array {
    return $this->explicitFormulas;
  }

  /**
   * {@inheritdoc}
   */
  public function getExplicitParamLabels(): array {
    return $this->explicitLabels;
  }

}
