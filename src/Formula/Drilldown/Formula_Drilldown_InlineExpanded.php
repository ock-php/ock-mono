<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Drilldown;

use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\IdToFormula\IdToFormula_FilterDecorator;
use Donquixote\Ock\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\UtilBase;

final class Formula_Drilldown_InlineExpanded extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\Ock\Formula\Id\Formula_IdInterface $idIsInline
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface|null
   */
  public static function create(Formula_DrilldownInterface $decorated, Formula_IdInterface $idIsInline, NurseryInterface $nursery): ?Formula_DrilldownInterface {

    $decoratedIdFormula = $decorated->getIdFormula();

    if (!$decoratedIdFormula instanceof Formula_SelectInterface) {
      return NULL;
    }

    $idFormula = new Formula_Select_InlineExpanded(
      $decoratedIdFormula,
      new IdToFormula_FilterDecorator(
        $decorated->getIdToFormula(),
        $idIsInline),
      $nursery);

    $idToFormula = new IdToFormula_InlineExpanded(
      $decorated->getIdToFormula(),
      $nursery);

    return new Formula_Drilldown(
      $idFormula,
      $idToFormula);
  }
}
