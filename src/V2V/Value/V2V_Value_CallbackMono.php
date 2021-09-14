<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Value;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

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
  public function phpGetPhp(string $php): string {
    $helper = new CodegenHelper();
    return $this->callback->argsPhpGetPhp([$php], $helper);

  }

}
