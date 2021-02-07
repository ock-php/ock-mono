<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Callback\Formula_Callback;
use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_FixedValue;

/**
 * @deprecated
 *   A definition should not include an instantiated object.
 *
 * @todo Remove this.
 */
class DefinitionToSchemaHelper_Handler implements DefinitionToSchemaHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function objectGetSchema($object): FormulaInterface {
    return new Formula_ValueProvider_FixedValue($object);
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): FormulaInterface {
    return new Formula_Callback($factory, $context);
  }
}
