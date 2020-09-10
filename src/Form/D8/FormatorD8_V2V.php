<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class FormatorD8_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\FormatorD8Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return FormatorD8::fromSchema(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createOptionable(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return FormatorD8::optionable(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

}
