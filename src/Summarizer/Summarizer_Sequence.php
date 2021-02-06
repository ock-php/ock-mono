<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
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
   * @param \Donquixote\OCUI\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\OCUI\Summarizer\Summarizer_Sequence|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ): ?Summarizer_Sequence {

    $itemSummarizer = Summarizer::fromSchema($schema->getItemSchema(), $schemaToAnything);

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
