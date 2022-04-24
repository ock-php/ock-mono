<?php

declare(strict_types=1);

namespace Donquixote\Ock\Evaluator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\EvaluatorException;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\GeneratorInterface;

class Evaluator_GeneratorEval implements EvaluatorInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Evaluator\EvaluatorInterface
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    #[Adaptee] FormulaInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): EvaluatorInterface {
    $generator = Generator::fromFormula($formula, $universalAdapter);
    return new self($generator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Generator\GeneratorInterface $generator
   */
  public function __construct(
    private GeneratorInterface $generator,
  ) {}

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
