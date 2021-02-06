<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Boolean implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Formula\Boolean\Formula_BooleanInterface $schema
   */
  public function __construct(Formula_BooleanInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $boolean = !empty($conf);

    return $boolean
      ? $this->schema->getTrueSummary()
      : $this->schema->getFalseSummary();
  }
}
