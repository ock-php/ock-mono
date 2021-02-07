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
  private $schema;

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $schema, FormulaToAnythingInterface $schemaToAnything): ?self {

    /** @var \Donquixote\OCUI\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = StaUtil::getMultiple(
      $schema->getItemFormulas(),
      $schemaToAnything,
      SummarizerInterface::class);

    if (NULL === $itemSummarizers) {
      return NULL;
    }

    return new self($schema, $itemSummarizers);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $schema
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface[] $itemSummarizers
   */
  public function __construct(Formula_GroupInterface $schema, array $itemSummarizers) {
    $this->schema = $schema;
    $this->itemSummarizers = $itemSummarizers;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $labels = $this->schema->getLabels();

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
