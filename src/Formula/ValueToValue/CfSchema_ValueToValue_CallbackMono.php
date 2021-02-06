<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Util\UtilBase;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_Value_CallbackMono;

final class CfSchema_ValueToValue_CallbackMono extends UtilBase {

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public static function fromStaticMethod(string $class, string $methodName, CfSchemaInterface $decorated): CfSchemaInterface {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return self::create($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public static function fromClass(string $class, CfSchemaInterface $decorated): CfSchemaInterface {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return self::create($decorated, $callback);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public static function create(CfSchemaInterface $decorated, CallbackReflectionInterface $callback): CfSchemaInterface {
    return new CfSchema_ValueToValue(
      $decorated,
      new V2V_Value_CallbackMono($callback));
  }
}
