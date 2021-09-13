<?php

declare(strict_types=1);

namespace Donquixote\Ock\Evaluator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\MessageUtil;

class Evaluator {

  /**
   * Materializes an evaluator from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface
   *   Materialized evaluator.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   *   Cannot build a evaluator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): EvaluatorInterface {

    /** @var \Donquixote\Ock\Evaluator\EvaluatorInterface $candidate */
    $candidate = $formulaToAnything->breed(
      $formula,
      EvaluatorInterface::class);

    return $candidate;
  }

  /**
   * Throws an exception for incompatible configuration.
   *
   * @param string $message
   *   Message describing the problem.
   *
   * @return never-return
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function incompatibleConfiguration(string $message) {
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

  /**
   * Throws an exception for incompatible configuration.
   *
   * @param string $expected
   *   Message describing the expected configuration.
   * @param mixed $conf
   *   Failing configuration.
   *
   * @return never-return
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function expectedConfigButFound(string $expected, $conf) {
    $message = $expected . ', found ' . MessageUtil::formatValue($conf);
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

}
