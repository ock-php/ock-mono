<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\MoreArgsVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\MoreArgs\Formula_MoreArgs;
use Donquixote\ObCK\Util\UtilBase;
use Donquixote\ObCK\V2V\Group\V2V_Group_Callback;

final class Formula_MoreArgsVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface
   */
  public static function fromClass(
    string $class,
    FormulaInterface $decorated,
    array $more
  ): Formula_MoreArgsValInterface {

    return self::create(
      CallbackReflection_ClassConstruction::createFromClassName(
        $class),
      $decorated,
      $more);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface
   */
  public static function fromStaticMethod(
    string $class,
    string $methodName,
    FormulaInterface $decorated,
    array $more
  ): Formula_MoreArgsValInterface {

    return self::create(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $decorated,
      $more);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface
   */
  public static function fromCallable(
    callable $callable,
    FormulaInterface $decorated,
    array $more
  ): Formula_MoreArgsValInterface {

    return self::create(
      CallbackUtil::callableGetCallback($callable),
      $decorated,
      $more);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\ObCK\Formula\MoreArgsVal\Formula_MoreArgsValInterface
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    FormulaInterface $decorated,
    array $more
  ): Formula_MoreArgsValInterface {

    return new Formula_MoreArgsVal(
      new Formula_MoreArgs($decorated, $more),
      new V2V_Group_Callback($callbackReflection));
  }
}
