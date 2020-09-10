<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Callback\CfSchema_Callback;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;

class DefinitionToSchemaHelper_Handler implements DefinitionToSchemaHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function objectGetSchema($object): CfSchemaInterface {
    return new CfSchema_ValueProvider_FixedValue($object);
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): CfSchemaInterface {
    return new CfSchema_Callback($factory, $context);
  }
}
