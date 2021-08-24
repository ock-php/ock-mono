<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\IdToFormula\IdToFormula_FilterDecorator;
use Donquixote\ObCK\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Formula_Drilldown_InlineExpanded extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\ObCK\Formula\Id\Formula_IdInterface $idIsInline
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface|null
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
