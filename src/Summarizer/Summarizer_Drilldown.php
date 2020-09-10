<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;

/**
 * @STA
 */
class Summarizer_Drilldown implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(
    CfSchema_DrilldownInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ) {
    $this->schema = $schema;
    $this->schemaToAnything = $schemaToAnything;
    $this->translator = $translator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf) {

    list($id, $subConf) = DrilldownKeysHelper::fromSchema($this->schema)
      ->unpack($conf);

    if (NULL === $id) {
      return '- ' . $this->translator->translate('None') . ' -';
    }

    if (NULL === $subSchema = $this->schema->getIdToSchema()->idGetSchema($id)) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    if (NULL === $idLabelUnsafe = $this->schema->getIdSchema()->idGetLabel($id)) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $idLabelSafe = HtmlUtil::sanitize($idLabelUnsafe);

    $subSummarizer = Summarizer::fromSchema(
      $subSchema,
      $this->schemaToAnything);

    if (NULL === $subSummarizer) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $subSummary = $subSummarizer->confGetSummary($subConf);

    if (!\is_string($subSummary) || '' === $subSummary) {
      return $idLabelSafe;
    }

    return $idLabelSafe . ': ' . $subSummary;
  }
}
