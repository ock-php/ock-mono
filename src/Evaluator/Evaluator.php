<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Evaluator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\MessageUtil;

class Evaluator {

  /**
   * Materializes an evaluator from a formula.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\Evaluator\EvaluatorInterface
   *   Materialized evaluator.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a evaluator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): EvaluatorInterface {

    /** @var \Donquixote\ObCK\Evaluator\EvaluatorInterface $candidate */
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
   * @return mixed
   *
   * @throws \Donquixote\ObCK\Exception\EvaluatorException_IncompatibleConfiguration
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
   * @return mixed
   *
   * @throws \Donquixote\ObCK\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function expectedConfigButFound(string $expected, $conf) {
    $message = $expected . ', found ' . MessageUtil::formatValue($conf);
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

}
