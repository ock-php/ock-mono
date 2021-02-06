<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchema_FilterDecorator;
use Donquixote\OCUI\IdToSchema\IdToSchema_InlineExpanded;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Formula\Select\CfSchema_Select_InlineExpanded;
use Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema_Drilldown_InlineExpanded extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idIsInline
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface|null
   */
  public static function create(Formula_DrilldownInterface $decorated, Formula_IdInterface $idIsInline): ?Formula_DrilldownInterface {

    $decoratedIdSchema = $decorated->getIdSchema();

    if (!$decoratedIdSchema instanceof CfSchema_SelectInterface) {
      return NULL;
    }

    $idSchema = new CfSchema_Select_InlineExpanded(
      $decoratedIdSchema,
      new IdToSchema_FilterDecorator(
        $decorated->getIdToSchema(),
        $idIsInline));

    $idToSchema = new IdToSchema_InlineExpanded(
      $decorated->getIdToSchema());

    return new CfSchema_Drilldown(
      $idSchema,
      $idToSchema);
  }
}
