<?php
declare(strict_types=1);

namespace Donquixote\Cf\Value;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface;

class Value implements ValueInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Cf\Value\ValueInterface|null
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function sequence(CfSchema_SequenceInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueInterface {

    $items = self::sequenceItems($schema, $conf, $scta);

    if (NULL === $items) {
      return NULL;
    }

    $value = [];
    foreach ($items as $k => $item) {
      $value[$k] = $item->getValue();
    }

    return new self($value);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Cf\Value\ValueInterface[]|null
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function sequenceItems(CfSchema_SequenceInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?array {

    if (!\is_array($conf)) {
      throw new EvaluatorException("Sequence conf must be an array.");
    }

    /** @var \Donquixote\Cf\Value\ValueInterface[] $items */
    $items = [];
    foreach ($conf as $delta => $deltaConf) {

      $item = self::fromSchemaConf($schema->getItemSchema(), $deltaConf, $scta);

      if (NULL === $item) {
        return NULL;
      }

      $items[$delta] = $item;
    }

    return $items;
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Cf\Value\ValueInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function group(CfSchema_GroupInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueInterface {

    $items = self::groupItems($schema, $conf, $scta);

    if (NULL === $items) {
      return NULL;
    }

    $value = [];
    foreach ($items as $k => $item) {
      $value[$k] = $item->getValue();
    }

    return new self($value);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\Cf\Value\ValueInterface[]
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private static function groupItems(CfSchema_GroupInterface $groupSchema, $conf, SchemaConfToAnythingInterface $scta): ?array {

    /** @var \Donquixote\Cf\Value\ValueInterface[] $items */
    $items = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {

      $itemConf = $conf[$k] ?? null;

      $itemValueStub = self::fromSchemaConf($itemSchema, $itemConf, $scta);

      if (NULL === $itemValueStub) {
        return NULL;
      }

      $items[$k] = $itemValueStub;
    }

    return $items;
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\Cf\Value\ValueInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function fromSchemaConf(CfSchemaInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueInterface {

    $object = $scta->schema($schema, $conf, ValueInterface::class);

    if (NULL === $object || !$object instanceof ValueInterface) {
      return NULL;
    }

    return $object;
  }

  /**
   * @param mixed $value
   */
  public function __construct($value) {
    $this->value = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return $this->value;
  }
}
