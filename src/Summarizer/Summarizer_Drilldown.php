<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Drilldown implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\Nursery\NurseryInterface
   */
  private $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   */
  public function __construct(
    Formula_DrilldownInterface $formula,
    NurseryInterface $formulaToAnything
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
      return Text::t('None')
        ->wrapSprintf('- %s -');
    }

    if (NULL === $subFormula = $this->formula->getIdToFormula()->idGetFormula($id)) {
      return Text::s($id)
        ->wrapT('@id', 'Unknown id "@id"')
        ->wrapSprintf('- %s -');
    }

    if (NULL === $idLabel = $this->formula->getIdFormula()->idGetLabel($id)) {
      return Text::s($id)
        ->wrapT('@id', 'Unnamed id "@id"')
        ->wrapSprintf('- %s -');
    }

    try {
      $subSummarizer = Summarizer::fromFormula(
        $subFormula,
        $this->formulaToAnything);
    }
    catch (IncarnatorException $e) {
      return Text::s($id)
        ->wrapT('@id', 'Undocumented id "@id"')
        ->wrapSprintf('- %s -');
    }

    $subSummary = $subSummarizer->confGetSummary($subConf);

    if ($subSummary === NULL) {
      return $idLabel;
    }

    // @todo Show just the label if subSummary produces empty string?
    return Text::label($idLabel, $subSummary);
  }
}
