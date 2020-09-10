<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\UtilBase;
use Donquixote\Cf\Zoo\V2V\Value\V2V_Value_CallbackMono;

final class CfSchema_ValueToValue_CallbackMono extends UtilBase {

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function fromStaticMethod($class, $methodName, CfSchemaInterface $decorated): CfSchemaInterface {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return self::create($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function fromClass($class, CfSchemaInterface $decorated): CfSchemaInterface {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return self::create($decorated, $callback);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create(CfSchemaInterface $decorated, CallbackReflectionInterface $callback): CfSchemaInterface {
    return new CfSchema_ValueToValue(
      $decorated,
      new V2V_Value_CallbackMono($callback));
  }
}
