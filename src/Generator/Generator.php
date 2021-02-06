<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   */
  public static function fromSchema(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?GeneratorInterface {

    $candidate = $schemaToAnything->schema(
      $schema,
      GeneratorInterface::class);

    if ($candidate instanceof GeneratorInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected an GeneratorInterface object or NULL.");
  }

}
