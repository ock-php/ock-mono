<?php
declare(strict_types=1);

namespace Donquixote\ObCK\DrilldownKeysHelper;

use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Util\UtilBase;

final class DrilldownKeysHelper extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   *
   * @return \Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface
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
   * @return \Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface
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
