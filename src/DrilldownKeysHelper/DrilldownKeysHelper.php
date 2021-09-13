<?php
declare(strict_types=1);

namespace Donquixote\Ock\DrilldownKeysHelper;

use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Util\UtilBase;

final class DrilldownKeysHelper extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   *
   * @return \Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface
   */
  public static function fromFormula(Formula_DrilldownInterface $drilldown): DrilldownKeysHelperInterface {
    return self::fromKeys(
      $drilldown->getIdKey(),
      $drilldown->getOptionsKey());
  }

  /**
   * @param string|int|null $idKey
   * @param string|int|null $optionsKey
   *
   * @return \Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface
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
