<?php
declare(strict_types=1);

namespace Donquixote\Cf\DrilldownKeysHelper;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Util\UtilBase;

final class DrilldownKeysHelper extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldown
   *
   * @return \Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  public static function fromSchema(CfSchema_DrilldownInterface $drilldown): DrilldownKeysHelperInterface {
    return self::fromKeys(
      $drilldown->getIdKey(),
      $drilldown->getOptionsKey());
  }

  /**
   * @param string|null $idKey
   * @param string|null $optionsKey
   *
   * @return \Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  public static function fromKeys($idKey, $optionsKey): DrilldownKeysHelperInterface {

    if (NULL === $idKey) {
      return new DrilldownKeysHelper_IdKeyNull();
    }

    if (NULL === $optionsKey) {
      return new DrilldownKeysHelper_OptionsKeyNull($idKey);
    }

    return new DrilldownKeysHelper_Default($idKey, $optionsKey);
  }
}
