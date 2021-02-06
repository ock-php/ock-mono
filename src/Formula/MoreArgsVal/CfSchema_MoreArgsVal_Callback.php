<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgsVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Formula\MoreArgs\CfSchema_MoreArgs;
use Donquixote\OCUI\Util\UtilBase;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_Callback;

final class CfSchema_MoreArgsVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface[] $more
   *
   * @return \Donquixote\OCUI\Formula\MoreArgsVal\CfSchema_MoreArgsValInterface
   */
  public static function fromClass(
    string $class,
    CfSchemaInterface $decorated,
    array $more
  ): CfSchema_MoreArgsValInterface {

    return self::create(
      CallbackReflection_ClassConstruction::createFromClassName(
        $class),
      $decorated,
      $more);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface[] $more
   *
   * @return \Donquixote\OCUI\Formula\MoreArgsVal\CfSchema_MoreArgsValInterface
   */
  public static function fromStaticMethod(
    string $class,
    string $methodName,
    CfSchemaInterface $decorated,
    array $more
  ): CfSchema_MoreArgsValInterface {

    return self::create(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $decorated,
      $more);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface[] $more
   *
   * @return \Donquixote\OCUI\Formula\MoreArgsVal\CfSchema_MoreArgsValInterface
   */
  public static function fromCallable(
    callable $callable,
    CfSchemaInterface $decorated,
    array $more
  ): CfSchema_MoreArgsValInterface {

    return self::create(
      CallbackUtil::callableGetCallback($callable),
      $decorated,
      $more);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface[] $more
   *
   * @return \Donquixote\OCUI\Formula\MoreArgsVal\CfSchema_MoreArgsValInterface
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    CfSchemaInterface $decorated,
    array $more
  ): CfSchema_MoreArgsValInterface {

    return new CfSchema_MoreArgsVal(
      new CfSchema_MoreArgs($decorated, $more),
      new V2V_Group_Callback($callbackReflection));
  }
}
