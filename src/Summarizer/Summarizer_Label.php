<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Schema\Label\CfSchema_LabelInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

/**
 * Decorator that prepends a "<label>: " to a summary.
 */
class Summarizer_Label implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\Label\CfSchema_LabelInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
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
   * Constructor.
   *
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\OCUI\Text\TextInterface $label
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

    return Text::label(
      $this->label,
      $decorated);
  }
}
