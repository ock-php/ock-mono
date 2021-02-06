<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @STA
 */
class Summarizer_Boolean implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface
   */
  private $schema;

  /**
   * @param \Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface $schema
   */
  public function __construct(CfSchema_BooleanInterface $schema) {
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
