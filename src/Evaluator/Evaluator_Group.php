<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class Evaluator_Group implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface[]
   */
  private $itemEvaluators;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Group {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromGroupValSchema(CfSchema_GroupValInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Group {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupInterface $groupSchema, V2V_GroupInterface $v2v, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Group {

    $itemEvaluators = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {
      $itemEvaluator = Evaluator::fromSchema($itemSchema, $schemaToAnything);
      if (NULL === $itemEvaluator) {
        return NULL;
      }
      $itemEvaluators[$k] = $itemEvaluator;
    }

    return new self($itemEvaluators, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface[] $itemEvaluators
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(array $itemEvaluators, V2V_GroupInterface $v2v) {
    $this->itemEvaluators = $itemEvaluators;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (!\is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $values = [];
    foreach ($this->itemEvaluators as $key => $itemEvaluator) {

      $itemConf = $conf[$key] ?? null;

      $values[$key] = $itemEvaluator->confGetValue($itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $phpStatements = [];
    foreach ($this->itemEvaluators as $key => $itemEvaluator) {

      $itemConf = $conf[$key] ?? null;

      $phpStatements[$key] = $itemEvaluator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
