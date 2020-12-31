<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;
use Donquixote\Cf\Util\HtmlUtil;

class Summarizer_Label implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_LabelInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Summarizer_Label {

    if (NULL === $decorated = Summarizer::fromSchema(
        $schema->getDecorated(),
        $schemaToAnything
      )
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\Cf\Text\TextInterface $label
   */
  public function __construct(SummarizerInterface $decorated, TextInterface $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $decorated = $this->decorated->confGetSummary($conf);

    if ('' === $decorated || NULL === $decorated) {
      return $decorated;
    }

    $label = HtmlUtil::sanitize($this->label);

    return Text::t('@label: @value', [
      '@label' => $label,
      '@value' => $decorated,
    ]);
  }
}
