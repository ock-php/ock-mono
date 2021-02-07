<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class Summarizer_Sequence implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $itemSummarizer;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\OCUI\Summarizer\Summarizer_Sequence|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_SequenceInterface $schema,
    FormulaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ): ?Summarizer_Sequence {

    $itemSummarizer = Summarizer::fromFormula($schema->getItemFormula(), $schemaToAnything);

    if (NULL === $itemSummarizer) {
      return NULL;
    }

    return new self($itemSummarizer);
  }

  /**
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $itemSummarizer
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

    $parts = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string) (int) $delta !== (string) $delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return Text::tParens('Noisy configuration.');
      }

      $parts[] = $this->itemSummarizer->confGetSummary($itemConf)
        ?? Text::tParens('Undocumented item.');
    }

    return Text::ol($parts);
  }
}
