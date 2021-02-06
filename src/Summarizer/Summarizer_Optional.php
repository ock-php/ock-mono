<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Util\StaUtil;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(
    Formula_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?SummarizerInterface {

    $decorated = Summarizer::fromSchema($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self($schema, $decorated);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface $schema
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $decorated
   */
  public function __construct(
    Formula_OptionalInterface $schema,
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
