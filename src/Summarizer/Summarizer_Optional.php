<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Util\StaUtil;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface
   */
  private $formula;

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $decorated
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
