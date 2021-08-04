<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Drilldown implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  private $formula;

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   */
  private $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  public function __construct(
    Formula_DrilldownInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ) {
    $this->formula = $formula;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    [$id, $subConf] = DrilldownKeysHelper::fromFormula($this->formula)
      ->unpack($conf);

    if (NULL === $id) {
      return Text::tSpecialOption('None');
    }

    if (NULL === $subFormula = $this->formula->getIdToFormula()->idGetFormula($id)) {
      return Text::tSpecialOption('Unknown id "@id".', [
        '@id' => Text::s($id),
      ]);
    }

    if (NULL === $idLabel = $this->formula->getIdFormula()->idGetLabel($id)) {
      return Text::tSpecialOption('Unnamed id "@id".', [
        '@id' => Text::s($id),
      ]);
    }

    $subSummarizer = Summarizer::fromFormula(
      $subFormula,
      $this->formulaToAnything);

    if (NULL === $subSummarizer) {
      return Text::tSpecialOption('Undocumented id "@id".', [
        '@id' => Text::s($id),
      ]);
    }

    $subSummary = $subSummarizer->confGetSummary($subConf);

    if ($subSummary === NULL) {
      return $idLabel;
    }

    // @todo Show just the label if subSummary produces empty string?
    return Text::label($idLabel, $subSummary);
  }
}
