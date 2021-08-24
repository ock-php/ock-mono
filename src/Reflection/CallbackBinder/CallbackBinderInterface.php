<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Reflection\CallbackBinder;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

interface CallbackBinderInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param true[] $freeParams
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface|null
   */
  public function bind(CallbackReflectionInterface $callback, array $freeParams = []): ?CallbackReflectionInterface;

}
