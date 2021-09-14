<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Optional\Formula_OptionalInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
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
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_OptionalInterface $formula,
    IncarnatorInterface $incarnator
  ): ?SummarizerInterface {

    $decorated = Summarizer::fromFormula($formula->getDecorated(), $incarnator);

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

    return $this->decorated->confGetSummary($conf['options'] ?? NULL);
  }
}
