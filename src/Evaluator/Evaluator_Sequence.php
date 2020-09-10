<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface;

class Evaluator_Sequence implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $itemEvaluator;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromSequenceSchema(CfSchema_SequenceInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Sequence {
    return self::create($schema, new V2V_Sequence_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\SequenceVal\CfSchema_SequenceValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromSequenceValSchema(CfSchema_SequenceValInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Sequence {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private static function create(CfSchema_SequenceInterface $schema, V2V_SequenceInterface $v2v, SchemaToAnythingInterface $schemaToAnything): ?Evaluator_Sequence {

    $itemEvaluator = Evaluator::fromSchema(
      $schema->getItemSchema(),
      $schemaToAnything
    );

    if (NULL === $itemEvaluator) {
      return NULL;
    }

    return new self($itemEvaluator, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $itemEvaluator
   * @param \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(EvaluatorInterface $itemEvaluator, V2V_SequenceInterface $v2v) {
    $this->itemEvaluator = $itemEvaluator;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      throw new EvaluatorException_IncompatibleConfiguration('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        $deltaExport = var_export($delta, TRUE);
        // Fail on non-numeric and negative keys.
        throw new EvaluatorException_IncompatibleConfiguration(''
          . "Deltas must be non-negative integers."
          . "\n" . "Found $deltaExport instead.");
      }

      $values[] = $this->itemEvaluator->confGetValue($itemConf);
    }

    return $this->v2v->valuesGetValue($values);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      return PhpUtil::incompatibleConfiguration("Configuration must be an array or NULL.");
    }

    $phpStatements = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return PhpUtil::incompatibleConfiguration("Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $this->itemEvaluator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
