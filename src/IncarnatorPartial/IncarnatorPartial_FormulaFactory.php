<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactoryInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

/**
 * @STA
 */
class IncarnatorPartial_FormulaFactory extends IncarnatorPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * Constructor.
   *
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    parent::__construct(
      Formula_FormulaFactoryInterface::class);
    $this->paramToValue = $paramToValue;
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {

    /** @var \Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactoryInterface $formula */

    try {
      $factory = $formula->getFormulaFactory();
    }
    catch (\Exception $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }

    $else = new \stdClass();
    $args = [];
    foreach ($factory->getReflectionParameters() as $param) {
      $arg = $this->paramToValue->paramGetValue($param, $else);
      if ($arg === $else) {
        throw new IncarnatorException(
          'Cannot find a value for parameter of formula factory.');
      }
      $args[] = $arg;
    }

    try {
      $candidate = $factory->invokeArgs($args);
    }
    catch (\Exception $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }

    if ($candidate === NULL) {
      return NULL;
    }

    if (!$candidate instanceof FormulaInterface) {
      throw new IncarnatorException('Expected a formula.');
    }

    return $candidate;
  }

}
