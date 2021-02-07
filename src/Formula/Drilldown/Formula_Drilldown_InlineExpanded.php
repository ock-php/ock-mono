<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToFormula\IdToFormula_FilterDecorator;
use Donquixote\OCUI\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\OCUI\Formula\Select\Formula_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Formula_Drilldown_InlineExpanded extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idIsInline
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface|null
   */
  public static function create(Formula_DrilldownInterface $decorated, Formula_IdInterface $idIsInline): ?Formula_DrilldownInterface {

    $decoratedIdFormula = $decorated->getIdFormula();

    if (!$decoratedIdFormula instanceof Formula_SelectInterface) {
      return NULL;
    }

    $idFormula = new Formula_Select_InlineExpanded(
      $decoratedIdFormula,
      new IdToFormula_FilterDecorator(
        $decorated->getIdToFormula(),
        $idIsInline));

    $idToFormula = new IdToFormula_InlineExpanded(
      $decorated->getIdToFormula());

    return new Formula_Drilldown(
      $idFormula,
      $idToFormula);
  }
}
