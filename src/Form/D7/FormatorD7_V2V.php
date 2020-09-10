<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class FormatorD7_V2V extends UtilBase {

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?FormatorD7Interface {
    return D7FormSTAUtil::formator(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createOptionable(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Optionable\OptionableFormatorD7Interface {
    return D7FormSTAUtil::formatorOptionable(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

}
