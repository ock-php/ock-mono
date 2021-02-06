<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Text\TextInterface;

class Summarizer_DefaultConf implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(
    Formula_DefaultConfInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Summarizer_DefaultConf {

    $decorated = Summarizer::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $schema->getDefaultConf());
  }

  /**
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(SummarizerInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetSummary($conf);
  }

}
