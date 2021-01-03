<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

/**
 * @internal
 *
 * These are helper objects used within
 * @see \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchema_Mappers,
 *
 */
interface DefinitionToSchemaHelperInterface {

  /**
   * @param object $object
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function objectGetSchema(object $object): CfSchemaInterface;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $factory
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): CfSchemaInterface;

}
