<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Form\D7\FormatorD7Interface;
use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class D7FormSTAUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function formator(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?FormatorD7Interface {
    return StaUtil::getObject($schema, $schemaToAnything, FormatorD7Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function formatorOptional(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD7Interface {

    $optionable = self::formatorOptionable(
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
   * @return \Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function formatorOptionable(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?OptionableFormatorD7Interface {
    return StaUtil::getObject($schema, $schemaToAnything, OptionableFormatorD7Interface::class);
  }
}
