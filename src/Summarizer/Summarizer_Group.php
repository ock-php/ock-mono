<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
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
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_GroupInterface $formula, UniversalAdapterInterface $universalAdapter): ?self {

    /** @var \Donquixote\Ock\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = FormulaAdapter::getMultiple(
      $formula->getItemFormulas(),
      SummarizerInterface::class,
      $universalAdapter);

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
