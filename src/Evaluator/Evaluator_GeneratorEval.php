<?php

declare(strict_types=1);

namespace Donquixote\Ock\Evaluator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\EvaluatorException;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\GeneratorInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Evaluator_GeneratorEval implements EvaluatorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private GeneratorInterface $generator;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(FormulaInterface $formula, IncarnatorInterface $incarnator): EvaluatorInterface {
    $generator = Generator::fromFormula($formula, $incarnator);
    return new self($generator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Generator\GeneratorInterface $generator
   */
  public function __construct(GeneratorInterface $generator) {
    $this->generator = $generator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    try {
      $php = $this->generator->confGetPhp($conf);
    }
    catch (GeneratorException $e) {
      throw new EvaluatorException($e->getMessage(), 0, $e);
    }
    // phpcs:ignore
    return eval($php);
  }

}
