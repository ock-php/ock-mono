<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactoryInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

/**
 * @STA
 */
class Cradle_FormulaFactory extends Cradle_FormulaReplacerBase {

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
  protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $nursery): ?FormulaInterface {

    /** @var \Donquixote\Ock\Formula\FormulaFactory\Formula_FormulaFactoryInterface $formula */

    $factory = $formula->getFormulaFactory();

    $else = new \stdClass();
    $args = [];
    foreach ($factory->getReflectionParameters() as $param) {
      $arg = $this->paramToValue->paramGetValue($param, $else);
      if ($arg === $else) {
        // @todo Throw or log.
        return NULL;
      }
      $args[] = $arg;
    }

    try {
      $candidate = $factory->invokeArgs($args);
    }
    catch (\Exception $e) {
      // @todo Throw or log.
      return NULL;
    }

    if ($candidate instanceof FormulaInterface) {
      return $candidate;
    }

    // @todo Throw or log.
    return NULL;
  }

}
