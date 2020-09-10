<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\FixedConf\CfSchema_FixedConfInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class Evaluator_FixedConf implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\FixedConf\CfSchema_FixedConfInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_FixedConfInterface $schema, SchemaToAnythingInterface $schemaToAnything): EvaluatorInterface {
    return new self(
      Evaluator::fromSchema($schema->getDecorated(), $schemaToAnything),
      $schema->getConf());
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param mixed $conf
   */
  public function __construct(EvaluatorInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    return $this->decorated->confGetValue($this->conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($this->conf);
  }
}
