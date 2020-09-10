<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Para\CfSchema_ParaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;

class Evaluator_Para implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $paraEvaluator;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Para\CfSchema_ParaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_ParaInterface $schema, SchemaToAnythingInterface $schemaToAnything): Evaluator_Para {
    return new self(
      Evaluator::fromSchema($schema->getDecorated(), $schemaToAnything),
      Evaluator::fromSchema($schema->getParaSchema(), $schemaToAnything));
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $paraEvaluator
   */
  public function __construct(EvaluatorInterface $decorated, EvaluatorInterface $paraEvaluator) {
    $this->decorated = $decorated;
    $this->paraEvaluator = $paraEvaluator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    $paraConf = $this->decorated->confGetValue($conf);
    return $this->paraEvaluator->confGetValue($paraConf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    try {
      $paraConf = $this->decorated->confGetValue($conf);
    }
    catch (EvaluatorException $e) {
      return PhpUtil::incompatibleConfiguration($e->getMessage());
    }

    return $this->paraEvaluator->confGetPhp($paraConf);
  }
}
