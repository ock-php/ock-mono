<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Optionlessness;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface;
use Donquixote\OCUI\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\OCUI\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Optionlessness\OptionlessnessInterface $schema
   *
   * @return \Donquixote\OCUI\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $schema): Optionlessness {
    return new Optionlessness($schema->isOptionless());
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   *
   * @return \Donquixote\OCUI\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ CfSchemaInterface $schema
  ): ?Optionlessness {
    return new Optionlessness(FALSE);
  }

}
