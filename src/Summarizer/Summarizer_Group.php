<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

class Summarizer_Group implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\Group\Formula_GroupInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_GroupInterface $formula, IncarnatorInterface $incarnator): ?self {

    /** @var \Donquixote\Ock\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = Incarnator::getMultiple(
      $formula->getItemFormulas(),
      SummarizerInterface::class,
      $incarnator);

    return new self($formula, $itemSummarizers);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface[] $itemSummarizers
   */
  public function __construct(Formula_GroupInterface $formula, array $itemSummarizers) {
    $this->formula = $formula;
    $this->itemSummarizers = $itemSummarizers;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $labels = $this->formula->getLabels();

    $parts = [];
    foreach ($this->itemSummarizers as $key => $itemSummarizer) {

      $itemSummary = $itemSummarizer->confGetSummary($conf[$key] ?? NULL);

      if ($itemSummary === NULL) {
        continue;
      }

      $parts[] = Text::label(
        $labels[$key] ?? Text::s($key),
        $itemSummary);
    }

    return $parts ? Text::ul($parts) : NULL;
  }
}
