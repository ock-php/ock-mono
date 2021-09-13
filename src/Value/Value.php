<?php
declare(strict_types=1);

namespace Donquixote\Ock\Value;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\EvaluatorException;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface;

class Value implements ValueInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @param \Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface $formula
   * @param $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\Value\ValueInterface|null
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function sequence(Formula_SequenceInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueInterface {

    $items = self::sequenceItems($formula, $conf, $scta);

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
   * @param \Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\Value\ValueInterface[]|null
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function sequenceItems(Formula_SequenceInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?array {

    if (!\is_array($conf)) {
      throw new EvaluatorException("Sequence conf must be an array.");
    }

    /** @var \Donquixote\Ock\Value\ValueInterface[] $items */
    $items = [];
    foreach ($conf as $delta => $deltaConf) {

      $item = self::fromFormulaConf($formula->getItemFormula(), $deltaConf, $scta);

      if (NULL === $item) {
        return NULL;
      }

      $items[$delta] = $item;
    }

    return $items;
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\Value\ValueInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function group(Formula_GroupInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueInterface {

    $items = self::groupItems($formula, $conf, $scta);

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
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\Ock\Value\ValueInterface[]
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  private static function groupItems(Formula_GroupInterface $groupFormula, $conf, FormulaConfToAnythingInterface $scta): ?array {

    /** @var \Donquixote\Ock\Value\ValueInterface[] $items */
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\Value\ValueInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromFormulaConf(FormulaInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ValueInterface {

    /** @var \Donquixote\Ock\Value\ValueInterface $object */
    $object = $scta->formula($formula, $conf, ValueInterface::class);

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
