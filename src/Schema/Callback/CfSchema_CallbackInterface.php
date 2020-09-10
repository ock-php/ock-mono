<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_CallbackInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback(): CallbackReflectionInterface;

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  public function getExplicitParamSchemas(): array;

  /**
   * @return string[]
   */
  public function getExplicitParamLabels(): array;

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
