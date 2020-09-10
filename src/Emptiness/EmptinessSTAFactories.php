<?php
declare(strict_types=1);

namespace Donquixote\Cf\Emptiness;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\Util\UtilBase;

final class EmptinessSTAFactories extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromOptionsSchema(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_SelectInterface $schema
  ): EmptinessInterface {
    return new Emptiness_Enum();
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromDrilldownSchema(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_DrilldownInterface $schema
  ): EmptinessInterface {
    return new Emptiness_Key('id');
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromOptionless(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_OptionlessInterface $schema
  ): EmptinessInterface {
    return new Emptiness_Bool();
  }

}
