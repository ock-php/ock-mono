<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Drilldown;

use Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface;

class V2V_Drilldown_FromIdV2V implements V2V_DrilldownInterface {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface
   */
  private $v2vId;

  /**
   * @param \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface $v2vId
   */
  public function __construct(V2V_IdInterface $v2vId) {
    $this->v2vId = $v2vId;
  }

  /**
   * {@inheritdoc}
   */
  public function idPhpGetPhp($id, string $php) {
    return $this->v2vId->idGetPhp($id);
  }
}
