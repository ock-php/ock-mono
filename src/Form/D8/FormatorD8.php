<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class FormatorD8 extends UtilBase {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   */
  public static function fromSchema(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD8Interface {

    $candidate = $schemaToAnything->schema(
      $schema,
      FormatorD8Interface::class);

    if (!$candidate instanceof FormatorD8Interface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   */
  public static function optional(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD8Interface {

    $optionable = self::optionable(
      $schema,
      $schemaToAnything
    );

    if (NULL === $optionable) {
      # kdpm('Sorry.', __METHOD__);
      return NULL;
    }

    return $optionable->getOptionalFormator();
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface|null
   */
  public static function optionable(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?OptionableFormatorD8Interface {
    return StaUtil::getObject($schema, $schemaToAnything, OptionableFormatorD8Interface::class);
  }

}
