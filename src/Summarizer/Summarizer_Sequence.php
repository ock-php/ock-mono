<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Translator\TranslatorInterface;

class Summarizer_Sequence implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private $itemSummarizer;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\Ock\Summarizer\Summarizer_Sequence|null
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_SequenceInterface $formula,
    NurseryInterface $formulaToAnything,
    TranslatorInterface $translator
  ): ?Summarizer_Sequence {

    $itemSummarizer = Summarizer::fromFormula($formula->getItemFormula(), $formulaToAnything);

    if (NULL === $itemSummarizer) {
      return NULL;
    }

    return new self($itemSummarizer);
  }

  /**
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $itemSummarizer
   */
  public function __construct(SummarizerInterface $itemSummarizer) {
    $this->itemSummarizer = $itemSummarizer;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $summary = Text::ol();
    foreach ($conf as $delta => $itemConf) {

      if ((string) (int) $delta !== (string) $delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return Text::t('Noisy configuration.')
          ->wrapSprintf('(%s)');
      }

      $summary->add(
        $this->itemSummarizer->confGetSummary($itemConf)
        ?? Text::t('Undocumented item.')
          ->wrapSprintf('(%s)'));
    }

    return $summary;
  }
}
