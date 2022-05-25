<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DrilldownVal;

use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\V2V\Drilldown\V2V_Drilldown_Arrify;
use Donquixote\Ock\V2V\Drilldown\V2V_Drilldown_Merge;
use Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface;

class Formula_DrilldownVal extends Formula_DrilldownValBase {

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param string|null $idKey
   * @param string|null $optionsKey
   *
   * @return self
   */
  public static function createArrify(Formula_DrilldownInterface $decorated, $idKey = NULL, $optionsKey = NULL): Formula_DrilldownVal {

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
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param string|null $idKey
   *
   * @return self
   */
  public static function createMerge(Formula_DrilldownInterface $decorated, $idKey = NULL): Formula_DrilldownVal {

    if (NULL === $idKey) {
      $idKey = $decorated->getIdKey();
    }

    return new self(
      $decorated,
      new V2V_Drilldown_Merge($idKey));
  }

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(
    Formula_DrilldownInterface $decorated,
    private readonly V2V_DrilldownInterface $v2v,
  ) {
    parent::__construct($decorated);
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_DrilldownInterface {
    return $this->v2v;
  }

}
