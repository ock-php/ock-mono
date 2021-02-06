<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_CallbackInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback(): CallbackReflectionInterface;

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface[]
   */
  public function getExplicitParamSchemas(): array;

  /**
   * @return string[]
   */
  public function getExplicitParamLabels(): array;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
