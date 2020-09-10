<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class Summarizer_DefaultConf implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_DefaultConfInterface $schema,
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
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(SummarizerInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf) {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetSummary($conf);
  }

}
