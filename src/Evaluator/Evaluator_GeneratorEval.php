<?php

declare(strict_types=1);

namespace Donquixote\Ock\Evaluator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\GeneratorInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

class Evaluator_GeneratorEval implements EvaluatorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private GeneratorInterface $generator;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(FormulaInterface $formula, NurseryInterface $nursery): EvaluatorInterface {
    $generator = Generator::fromFormula($formula, $nursery);
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
    $php = $this->generator->confGetPhp($conf);
    return eval($php);
  }

}
