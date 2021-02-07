<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Util\StaUtil;

class Summarizer_Group implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Group\Formula_GroupInterface
   */
  private $formula;

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?self {

    /** @var \Donquixote\OCUI\Summarizer\SummarizerInterface[] $itemSummarizers */
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
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface[] $itemSummarizers
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
