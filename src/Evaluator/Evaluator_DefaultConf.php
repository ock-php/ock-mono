<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

/**
 * @see \Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConfInterface
 */
class Evaluator_DefaultConf implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
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
  ): ?self {

    $decorated = Evaluator::fromSchema(
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
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(EvaluatorInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetValue($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetPhp($conf);
  }
}
