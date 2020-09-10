<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_Drilldown_Arrify;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_Drilldown_Merge;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

class CfSchema_DrilldownVal extends CfSchema_DrilldownValBase {

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param string|null $idKey
   * @param string|null $optionsKey
   *
   * @return self
   */
  public static function createArrify(CfSchema_DrilldownInterface $decorated, $idKey = NULL, $optionsKey = NULL): CfSchema_DrilldownVal {

    if (NULL === $idKey) {
      $idKey = $decorated->getIdKey();
    }

    if (NULL === $optionsKey) {
      $optionsKey = $decorated->getOptionsKey();
    }

    return new self(
      $decorated,
      new V2V_Drilldown_Arrify($idKey, $optionsKey));
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param string|null $idKey
   *
   * @return self
   */
  public static function createMerge(CfSchema_DrilldownInterface $decorated, $idKey = NULL): CfSchema_DrilldownVal {

    if (NULL === $idKey) {
      $idKey = $decorated->getIdKey();
    }

    return new self(
      $decorated,
      new V2V_Drilldown_Merge($idKey));
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(CfSchema_DrilldownInterface $decorated, V2V_DrilldownInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_DrilldownInterface {
    return $this->v2v;
  }
}
