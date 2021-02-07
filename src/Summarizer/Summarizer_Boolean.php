<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Boolean implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface
   */
  private $formula;

  /**
   * @param \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface $formula
   */
  public function __construct(Formula_BooleanInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $boolean = !empty($conf);

    return $boolean
      ? $this->formula->getTrueSummary()
      : $this->formula->getFalseSummary();
  }
}
