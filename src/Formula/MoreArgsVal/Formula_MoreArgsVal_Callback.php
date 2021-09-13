<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\MoreArgsVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgs;
use Donquixote\Ock\Util\UtilBase;
use Donquixote\Ock\V2V\Group\V2V_Group_Callback;

final class Formula_MoreArgsVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface
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
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[] $more
   *
   * @return \Donquixote\Ock\Formula\MoreArgsVal\Formula_MoreArgsValInterface
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
