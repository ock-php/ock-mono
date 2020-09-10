<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Value;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Cf\Exception\EvaluatorException;

class V2V_Value_CallbackMono implements V2V_ValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * {@inheritdoc}
   */
  public function valueGetValue($value) {

    try {
      return $this->callback->invokeArgs([$value]);
    }
    catch (\Exception $e) {
      throw new EvaluatorException("Exception in callback.", 0, $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function phpGetPhp(string $php): string {
    $helper = new CodegenHelper();
    return $this->callback->argsPhpGetPhp([$php], $helper);

  }
}
