<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface;

/**
 * @STA
 */
class Summarizer_Boolean implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface $schema
   */
  public function __construct(CfSchema_BooleanInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf) {

    $boolean = !empty($conf);

    return $boolean
      ? $this->schema->getTrueSummary()
      : $this->schema->getFalseSummary();
  }
}
