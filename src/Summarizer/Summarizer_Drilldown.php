<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Drilldown implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(
    CfSchema_DrilldownInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    $this->schema = $schema;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    [$id, $subConf] = DrilldownKeysHelper::fromSchema($this->schema)
      ->unpack($conf);

    if (NULL === $id) {
      return Text::tSpecialOption('None');
    }

    if (NULL === $subSchema = $this->schema->getIdToSchema()->idGetSchema($id)) {
      return Text::tSpecialOption('Unknown id "@id".', [
        '@id' => $id,
      ]);
    }

    if (NULL === $idLabel = $this->schema->getIdSchema()->idGetLabel($id)) {
      return Text::tSpecialOption('Unnamed id "@id".', [
        '@id' => $id,
      ]);
    }

    $subSummarizer = Summarizer::fromSchema(
      $subSchema,
      $this->schemaToAnything);

    if (NULL === $subSummarizer) {
      return Text::tSpecialOption('Undocumented id "@id".', [
        '@id' => $id,
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
