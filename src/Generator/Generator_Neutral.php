<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Generator_Neutral {

  /**
   * @param \Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function create(Formula_SkipEvaluatorInterface $formula, IncarnatorInterface $incarnator): ?GeneratorInterface {

    return Generator::fromFormula($formula->getDecorated(), $incarnator);
  }

}
