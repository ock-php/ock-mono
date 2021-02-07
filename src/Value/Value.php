<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Value;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\EvaluatorException;
use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface;

class Value implements ValueInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $schema
   * @param $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Value\ValueInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\EvaluatorException
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function sequence(Formula_SequenceInterface $schema, $conf, FormulaConfToAnythingInterface $scta): ?ValueInterface {

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
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Value\ValueInterface[]|null
   *
   * @throws \Donquixote\OCUI\Exception\EvaluatorException
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function sequenceItems(Formula_SequenceInterface $schema, $conf, FormulaConfToAnythingInterface $scta): ?array {

    if (!\is_array($conf)) {
      throw new EvaluatorException("Sequence conf must be an array.");
    }

    /** @var \Donquixote\OCUI\Value\ValueInterface[] $items */
    $items = [];
    foreach ($conf as $delta => $deltaConf) {

      $item = self::fromFormulaConf($schema->getItemFormula(), $deltaConf, $scta);

      if (NULL === $item) {
        return NULL;
      }

      $items[$delta] = $item;
    }

    return $items;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Value\ValueInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function group(Formula_GroupInterface $schema, $conf, FormulaConfToAnythingInterface $scta): ?ValueInterface {

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
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $groupFormula
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\OCUI\Value\ValueInterface[]
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  private static function groupItems(Formula_GroupInterface $groupFormula, $conf, FormulaConfToAnythingInterface $scta): ?array {

    /** @var \Donquixote\OCUI\Value\ValueInterface[] $items */
    $items = [];
    foreach ($groupFormula->getItemFormulas() as $k => $itemFormula) {

      $itemConf = $conf[$k] ?? null;

      $itemValueStub = self::fromFormulaConf($itemFormula, $itemConf, $scta);

      if (NULL === $itemValueStub) {
        return NULL;
      }

      $items[$k] = $itemValueStub;
    }

    return $items;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\OCUI\Value\ValueInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function fromFormulaConf(FormulaInterface $schema, $conf, FormulaConfToAnythingInterface $scta): ?ValueInterface {

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
