<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Text\TextInterface;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?SummarizerInterface {

    $decorated = Summarizer::fromSchema($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptiness = StaUtil::emptinessOrNull($schema, $schemaToAnything);

    if (NULL === $emptiness) {
      return new self($schema, $decorated);
    }

    return new Summarizer_OptionalWithEmptiness(
      $schema,
      $decorated,
      $emptiness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerInterface $decorated
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptySummary();
    }

    return $this->decorated->confGetSummary($conf['options'] ?? null);
  }
}
