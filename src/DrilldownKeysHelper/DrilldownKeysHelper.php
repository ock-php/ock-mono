<?php
declare(strict_types=1);

namespace Donquixote\OCUI\DrilldownKeysHelper;

use Donquixote\OCUI\Formula\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\OCUI\Util\UtilBase;

final class DrilldownKeysHelper extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\CfSchema_DrilldownInterface $drilldown
   *
   * @return \Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  public static function fromSchema(CfSchema_DrilldownInterface $drilldown): DrilldownKeysHelperInterface {
    return self::fromKeys(
      $drilldown->getIdKey(),
      $drilldown->getOptionsKey());
  }

  /**
   * @param string|int|null $idKey
   * @param string|int|null $optionsKey
   *
   * @return \Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface
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
