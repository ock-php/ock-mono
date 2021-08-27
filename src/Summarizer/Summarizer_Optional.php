<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Optional\Formula_OptionalInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Text\TextInterface;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Optional\Formula_OptionalInterface
   */
  private $formula;

  /**
   * @var \Donquixote\ObCK\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_OptionalInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?SummarizerInterface {

    $decorated = Summarizer::fromFormula($formula->getDecorated(), $formulaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self($formula, $decorated);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\ObCK\Summarizer\SummarizerInterface $decorated
   */
  public function __construct(
    Formula_OptionalInterface $formula,
    SummarizerInterface $decorated
  ) {
    $this->formula = $formula;
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->formula->getEmptySummary();
    }

    return $this->decorated->confGetSummary($conf['options'] ?? null);
  }
}
