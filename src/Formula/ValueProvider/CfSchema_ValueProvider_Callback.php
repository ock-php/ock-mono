<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueProvider;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\OCUI\Exception\EvaluatorException;

class CfSchema_ValueProvider_Callback implements CfSchema_ValueProviderInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   *
   * @return \Donquixote\OCUI\Formula\ValueProvider\CfSchema_ValueProvider_Callback
   */
  public static function fromClass(string $class): CfSchema_ValueProvider_Callback {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return new self($callback);
  }

  /**
   * @param string $class
   * @param string $methodName
   *
   * @return \Donquixote\OCUI\Formula\ValueProvider\CfSchema_ValueProvider_Callback
   */
  public static function createFromClassStaticMethod(string $class, string $methodName): CfSchema_ValueProvider_Callback {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($callback);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    try {
      return $this->callback->invokeArgs([]);
    }
    catch (\Exception $e) {
      throw new EvaluatorException("Exception in callback", 0, $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {
    $helper = new CodegenHelper();
    return $this->callback->argsPhpGetPhp([], $helper);
  }
}
