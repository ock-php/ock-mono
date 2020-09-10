<?php
declare(strict_types=1);

namespace Donquixote\Cf\Optionlessness;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Optionlessness\OptionlessnessInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $schema): Optionlessness {
    return new Optionlessness($schema->isOptionless());
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ CfSchemaInterface $schema
  ): ?Optionlessness {
    return new Optionlessness(FALSE);
  }

}
