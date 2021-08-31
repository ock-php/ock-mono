<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Group\GroupFormulaBuilder;
use Donquixote\ObCK\Formula\Iface\Formula_Iface;
use Donquixote\ObCK\Formula\Select\Flat\FlatSelectBuilderInterface;
use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelect_Fixed;
use Donquixote\ObCK\Formula\Sequence\Formula_Sequence;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Formula extends UtilBase {

  /**
   * @return \Donquixote\ObCK\Formula\Select\Flat\FlatSelectBuilderInterface
   */
  public static function flatSelect(): FlatSelectBuilderInterface {
    return new Formula_FlatSelect_Fixed([]);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function iface(string $interface): FormulaInterface {
    return new Formula_Iface($interface);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function ifaceOrNull(string $interface): FormulaInterface {
    return new Formula_Iface($interface, TRUE);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\ObCK\Formula\Sequence\Formula_Sequence
   */
  public static function ifaceSequence(string $interface): Formula_Sequence {
    return new Formula_Sequence(
      new Formula_Iface($interface));
  }

  /**
   * @return \Donquixote\ObCK\Formula\Group\GroupFormulaBuilder
   */
  public static function group(): GroupFormulaBuilder {
    return new GroupFormulaBuilder();
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formula_to_anything
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function replace(FormulaInterface $formula, FormulaToAnythingInterface $formula_to_anything): ?FormulaInterface {

    $candidate = $formula_to_anything->formula(
      $formula,
      FormulaInterface::class);

    if ($candidate instanceof FormulaInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected a FormulaInterface object or NULL.");
  }

}
