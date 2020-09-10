<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface;

class Evaluator_ValueToValue extends Evaluator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_ValueToValueInterface $valueToValueSchema, SchemaToAnythingInterface $schemaToAnything): ?self {

    $decorated = $schemaToAnything->schema(
      $valueToValueSchema->getDecorated(),
      EvaluatorInterface::class);

    if (NULL === $decorated || !$decorated instanceof EvaluatorInterface) {
      return NULL;
    }

    return new self($decorated, $valueToValueSchema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param \Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(EvaluatorInterface $decorated, V2V_ValueInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    return $this->v2v->valueGetValue($value);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    $php = parent::confGetPhp($conf);
    return $this->v2v->phpGetPhp($php);
  }
}
