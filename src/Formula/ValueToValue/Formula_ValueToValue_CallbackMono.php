<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Util\UtilBase;
use Donquixote\Ock\V2V\Value\V2V_Value_CallbackMono;

final class Formula_ValueToValue_CallbackMono extends UtilBase {

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function fromStaticMethod(string $class, string $methodName, FormulaInterface $decorated): FormulaInterface {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return self::create($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function fromClass(string $class, FormulaInterface $decorated): FormulaInterface {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return self::create($decorated, $callback);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function create(FormulaInterface $decorated, CallbackReflectionInterface $callback): FormulaInterface {
    return new Formula_ValueToValue(
      $decorated,
      new V2V_Value_CallbackMono($callback));
  }
}
