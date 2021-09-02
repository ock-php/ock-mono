<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Value;

use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

class V2V_Value_GroupV2V implements V2V_ValueInterface {

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private V2V_GroupInterface $groupV2V;

  /**
   * @var string
   */
  private string $key;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $groupV2V
   * @param string $key
   */
  public function __construct(V2V_GroupInterface $groupV2V, string $key) {
    $this->groupV2V = $groupV2V;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function phpGetPhp(string $php): string {
    return $this->groupV2V->itemsPhpGetPhp([$this->key => $php]);
  }

}
