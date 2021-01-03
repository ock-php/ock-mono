<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Group implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?self {

    /** @var \Donquixote\Cf\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = StaUtil::getMultiple(
      $schema->getItemSchemas(),
      $schemaToAnything,
      SummarizerInterface::class);

    if (NULL === $itemSummarizers) {
      return NULL;
    }

    return new self($schema, $itemSummarizers);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface[] $itemSummarizers
   */
  public function __construct(CfSchema_GroupInterface $schema, array $itemSummarizers) {
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
