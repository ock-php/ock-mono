<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\Cf\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class Evaluator_MoreArgs extends Evaluator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface[]
   */
  private $moreEvaluators;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var mixed[]|null
   */
  private $commonValues;

  /**
   * @var string[]|null
   */
  private $commonValuesPhp;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromMoreArgsSchema(CfSchema_MoreArgsInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_MoreArgs {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromMoreArgsValSchema(
    CfSchema_MoreArgsValInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Evaluator_MoreArgs {
    return self::create(
      $schema->getDecorated(),
      $schema->getV2V(),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $moreArgsSchema
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_MoreArgsInterface $moreArgsSchema,
    V2V_GroupInterface $v2v,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Evaluator_MoreArgs {

    $decoratedEvaluator = Evaluator::fromSchema(
      $moreArgsSchema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decoratedEvaluator) {
      return NULL;
    }

    $moreEvaluators = [];
    foreach ($moreArgsSchema->getMoreArgs() as $k => $itemSchema) {
      $itemEvaluator = Evaluator::fromSchema($itemSchema, $schemaToAnything);
      if (NULL === $itemEvaluator) {
        return NULL;
      }
      $moreEvaluators[$k] = $itemEvaluator;
    }

    return new self(
      $decoratedEvaluator,
      $moreEvaluators,
      $moreArgsSchema->getSpecialKey(),
      $v2v);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface[] $moreEvaluators
   * @param string|int $specialKey
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(
    EvaluatorInterface $decorated,
    array $moreEvaluators,
    $specialKey,
    V2V_GroupInterface $v2v
  ) {
    parent::__construct($decorated);
    $this->moreEvaluators = $moreEvaluators;
    $this->specialKey = $specialKey;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    $values = $this->getCommonValues();
    $values[$this->specialKey] = parent::confGetValue($conf);

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * @return mixed[]
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  private function getCommonValues(): array {
    return $this->commonValues
      ?? $this->commonValues = $this->buildCommonValues();
  }

  /**
   * @return mixed[]
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  private function buildCommonValues(): array {

    $commonValues = [];
    $commonValues[$this->specialKey] = NULL;
    foreach ($this->moreEvaluators as $k => $evaluator) {
      $commonValues[$k] = $evaluator->confGetValue(NULL);
    }

    return $commonValues;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $valuesPhp = $this->getCommonValuesPhp();
    $valuesPhp[$this->specialKey] = parent::confGetPhp($conf);

    return $this->v2v->valuesGetValue($valuesPhp);
  }

  /**
   * @return string[]
   */
  private function getCommonValuesPhp(): array {
    return $this->commonValuesPhp
      ?? $this->commonValuesPhp = $this->buildCommonValuesPhp();
  }

  /**
   * @return string[]
   */
  private function buildCommonValuesPhp(): array {

    $commonValuesPhp = [];
    $commonValuesPhp[$this->specialKey] = NULL;
    foreach ($this->moreEvaluators as $k => $evaluator) {
      $commonValuesPhp[$k] = $evaluator->confGetPhp(NULL);
    }

    return $commonValuesPhp;
  }
}
