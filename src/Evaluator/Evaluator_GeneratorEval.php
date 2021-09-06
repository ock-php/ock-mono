<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Evaluator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Generator\Generator;
use Donquixote\ObCK\Generator\GeneratorInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;

class Evaluator_GeneratorEval implements EvaluatorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private GeneratorInterface $generator;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\ObCK\Evaluator\EvaluatorInterface
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(FormulaInterface $formula, NurseryInterface $nursery): EvaluatorInterface {
    $generator = Generator::fromFormula($formula, $nursery);
    return new self($generator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $generator
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
