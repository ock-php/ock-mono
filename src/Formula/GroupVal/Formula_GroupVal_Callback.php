<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\OCUI\Formula\Group\Formula_Group;
use Donquixote\OCUI\Util\UtilBase;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_Callback;

final class Formula_GroupVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function fromClass(string $class, array $formulas, array $labels): Formula_GroupValInterface {

    return self::create(
      CallbackReflection_ClassConstruction::createFromClassName(
        $class),
      $formulas,
      $labels);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function fromStaticMethod(string $class, string $methodName, array $formulas, array $labels): Formula_GroupValInterface {

    return self::create(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $formulas,
      $labels);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function fromCallable(callable $callable, array $formulas, array $labels): Formula_GroupValInterface {

    return self::create(
      CallbackUtil::callableGetCallback($callable),
      $formulas,
      $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    array $formulas,
    array $labels
  ): Formula_GroupValInterface {

    return new Formula_GroupVal(
      new Formula_Group($formulas, $labels),
      new V2V_Group_Callback($callbackReflection));
  }
}
