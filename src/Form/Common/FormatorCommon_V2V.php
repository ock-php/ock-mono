<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\Common;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

/**
 * @STA
 */
class FormatorCommon_V2V implements SchemaToAnythingPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ): ?object {

    if (!$schema instanceof CfSchema_ValueToValueBaseInterface) {
      return NULL;
    }

    return $helper->schema($schema->getDecorated(), $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass(string $schemaClass): bool {
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
