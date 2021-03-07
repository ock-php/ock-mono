<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Group\GroupFormulaBuilder;
use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\OCUI\Formula\Iface\Formula_Iface;
use Donquixote\OCUI\Formula\Select\Flat\FlatSelectBuilderInterface;
use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelect_Fixed;
use Donquixote\OCUI\Formula\Sequence\Formula_Sequence;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Formula extends UtilBase {

  /**
   * @return \Donquixote\OCUI\Formula\Select\Flat\FlatSelectBuilderInterface
   */
  public static function flatSelect(): FlatSelectBuilderInterface {
    return new Formula_FlatSelect_Fixed([]);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function iface(string $interface): FormulaInterface {
    return new Formula_Iface($interface);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function ifaceOrNull(string $interface): FormulaInterface {
    return new Formula_Iface($interface, TRUE);
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function ifaceSequence(string $interface): FormulaInterface {
    return new Formula_Sequence(
      new Formula_Iface($interface));
  }

  /**
   * @return \Donquixote\OCUI\Formula\Group\GroupFormulaBuilder
   */
  public static function group(): GroupFormulaBuilder {
    return new GroupFormulaBuilder();
  }

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
