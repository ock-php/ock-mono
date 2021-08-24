<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_CallbackNoHelper extends FormulaToAnythingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string|null $formulaType
   *
   * @return self
   */
  public static function fromClassName(string $class, $formulaType = NULL): self {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self(
      $callback,
      $formulaType,
      $class);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $resultType
   *
   * @return \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface|null
   */
  public static function create(CallbackReflectionInterface $callback, $resultType = NULL): ?FormulaToAnythingPartialInterface {

    $params = $callback->getReflectionParameters();

    if ([0] !== array_keys($params)) {
      return NULL;
    }

    if (NULL === $t0 = $params[0]->getClass()) {
      return NULL;
    }

    if (FormulaInterface::class === $formulaType = $t0->getName()) {
      $formulaType = NULL;
    }
    elseif (!is_a($formulaType, FormulaInterface::class, TRUE)) {
      return NULL;
    }

    return new self($callback, $formulaType, $resultType);
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $formulaType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $formulaType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($formulaType, $resultType);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    try {
      return $this->callback->invokeArgs([$formula]);
    }
    catch (\Exception $e) {
      throw new FormulaToAnythingException("Exception in callback.", 0, $e);
    }
  }
}
