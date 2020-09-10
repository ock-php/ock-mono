<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class Evaluator_Optional implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_OptionalInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?EvaluatorInterface {

    $decorated = Evaluator::fromSchema($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptiness = StaUtil::emptinessOrNull($schema->getDecorated(), $schemaToAnything);

    if (NULL === $emptiness) {
      return new self(
        $decorated,
        $schema);
    }

    return new Evaluator_OptionalWithEmptiness(
      $decorated,
      $schema,
      $emptiness);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(EvaluatorInterface $decorated, CfSchema_OptionalInterface $schema) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyValue();
    }

    $subConf = $conf['options'] ?? null;

    return $this->decorated->confGetValue($subConf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyPhp();
    }

    $subConf = $conf['options'] ?? null;

    return $this->decorated->confGetPhp($subConf);
  }
}
