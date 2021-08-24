<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Util\StaUtil;

class Summarizer_Group implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Group\Formula_GroupInterface
   */
  private $formula;

  /**
   * @var \Donquixote\ObCK\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?self {

    /** @var \Donquixote\ObCK\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = StaUtil::getMultiple(
      $formula->getItemFormulas(),
      $formulaToAnything,
      SummarizerInterface::class);

    if (NULL === $itemSummarizers) {
      return NULL;
    }

    return new self($formula, $itemSummarizers);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\Summarizer\SummarizerInterface[] $itemSummarizers
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

      $itemSummary = $itemSummarizer->confGetSummary($conf[$key] ?? null);

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
