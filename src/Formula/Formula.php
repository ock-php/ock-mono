<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Group\GroupFormulaBuilder;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Formula\Select\Flat\FlatSelectBuilderInterface;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelect_Fixed;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class Formula extends UtilBase {

  /**
   * Validates one or more formulas.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface ...$formulas
   *   Formulas to validate.
   *   In PHP < 8.0, string keys are not allowed here.
   */
  public static function validate(FormulaInterface ...$formulas) {}

  /**
   * Validates an array of formulas.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $formulas
   *   Array of formulas to validate.
   *   This array can have string keys.
   */
  public static function validateMultiple(array $formulas) {
    self::validate(...array_values($formulas));
  }

  /**
   * @return \Donquixote\Ock\Formula\Select\Flat\FlatSelectBuilderInterface
   */
  public static function flatSelect(): FlatSelectBuilderInterface {
    return new Formula_FlatSelect_Fixed([]);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function iface(string $interface): FormulaInterface {
    return new Formula_Iface($interface);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function ifaceOrNull(string $interface): FormulaInterface {
    return new Formula_Iface($interface, TRUE);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Ock\Formula\Sequence\Formula_Sequence
   */
  public static function ifaceSequence(string $interface): Formula_Sequence {
    return new Formula_Sequence(
      new Formula_Iface($interface));
  }

  /**
   * @return \Donquixote\Ock\Formula\Group\GroupFormulaBuilder
   */
  public static function group(): GroupFormulaBuilder {
    return new GroupFormulaBuilder();
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function replace(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {

    $candidate = Incarnator::getObject(
      $formula,
      FormulaInterface::class,
      $incarnator);

    if ($candidate instanceof FormulaInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected a FormulaInterface object or NULL.");
  }

}
