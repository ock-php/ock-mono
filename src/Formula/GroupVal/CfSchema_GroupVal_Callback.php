<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\OCUI\Formula\Group\CfSchema_Group;
use Donquixote\OCUI\Util\UtilBase;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_Callback;

final class CfSchema_GroupVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\CfSchema_GroupValInterface
   */
  public static function fromClass(string $class, array $schemas, array $labels): CfSchema_GroupValInterface {

    return self::create(
      CallbackReflection_ClassConstruction::createFromClassName(
        $class),
      $schemas,
      $labels);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\CfSchema_GroupValInterface
   */
  public static function fromStaticMethod(string $class, string $methodName, array $schemas, array $labels): CfSchema_GroupValInterface {

    return self::create(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $schemas,
      $labels);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\CfSchema_GroupValInterface
   */
  public static function fromCallable(callable $callable, array $schemas, array $labels): CfSchema_GroupValInterface {

    return self::create(
      CallbackUtil::callableGetCallback($callable),
      $schemas,
      $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\OCUI\Formula\GroupVal\CfSchema_GroupValInterface
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    array $schemas,
    array $labels
  ): CfSchema_GroupValInterface {

    return new CfSchema_GroupVal(
      new CfSchema_Group($schemas, $labels),
      new V2V_Group_Callback($callbackReflection));
  }
}
