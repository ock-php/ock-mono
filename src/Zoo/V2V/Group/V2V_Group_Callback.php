<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class V2V_Group_Callback implements V2V_GroupInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   */
  public function __construct(CallbackReflectionInterface $callbackReflection) {
    $this->callbackReflection = $callbackReflection;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    // @todo Does the helper need to be passed into this method?
    $helper = new CodegenHelper();
    return $this->callbackReflection->argsPhpGetPhp($itemsPhp, $helper);
  }
}
