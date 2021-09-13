<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Optional\Formula_OptionalInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Text\TextInterface;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_OptionalInterface $formula,
    NurseryInterface $formulaToAnything
  ): ?SummarizerInterface {

    $decorated = Summarizer::fromFormula($formula->getDecorated(), $formulaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self($formula, $decorated);
  }

  /**
   * @param \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $decorated
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
