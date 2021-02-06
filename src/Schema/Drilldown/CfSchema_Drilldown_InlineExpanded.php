<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchema_FilterDecorator;
use Donquixote\OCUI\IdToSchema\IdToSchema_InlineExpanded;
use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\Schema\Select\CfSchema_Select_InlineExpanded;
use Donquixote\OCUI\Schema\Select\CfSchema_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema_Drilldown_InlineExpanded extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface $idIsInline
   *
   * @return \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface|null
   */
  public static function create(CfSchema_DrilldownInterface $decorated, CfSchema_IdInterface $idIsInline): ?CfSchema_DrilldownInterface {

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
