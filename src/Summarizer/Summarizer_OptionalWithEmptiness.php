<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Text\TextInterface;

class Summarizer_OptionalWithEmptiness implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerInterface $decorated,
    EmptinessInterface $emptiness
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
    $this->emptiness = $emptiness;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    return $this->emptiness->confIsEmpty($conf)
      ? $this->schema->getEmptySummary()
      : $this->decorated->confGetSummary($conf);
  }
}
