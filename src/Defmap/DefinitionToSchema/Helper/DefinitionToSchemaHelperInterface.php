<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchema_Mappers,
 *
 */
interface DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\OCUI\Exception\CfSchemaCreationException
   */
  public function objectGetSchema(object $object): CfSchemaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\OCUI\Exception\CfSchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): CfSchemaInterface;

}
