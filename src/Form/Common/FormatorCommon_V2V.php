<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\Common;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

/**
 * @STA
 */
class FormatorCommon_V2V implements SchemaToAnythingPartialInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    if (!$schema instanceof CfSchema_ValueToValueBaseInterface) {
      return NULL;
    }

    return $helper->schema($schema->getDecorated(), $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType($resultInterface): bool {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass($schemaClass): bool {
    return is_a(
      $schemaClass,
      CfSchema_ValueToValueBaseInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
