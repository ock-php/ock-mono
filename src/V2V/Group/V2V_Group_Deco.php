<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

use Donquixote\ObCK\Util\DecoUtil;

class V2V_Group_Deco implements V2V_GroupInterface {

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private V2V_GroupInterface $decoratedV2V;

  /**
   * Static factory.
   *
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface|null $decorated_v2v
   *
   * @return self
   */
  public static function create(?V2V_GroupInterface $decorated_v2v): self {
    return new self($decorated_v2v ?? new V2V_Group_Trivial());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $decorated_v2v
   */
  public function __construct(V2V_GroupInterface $decorated_v2v) {
    $this->decoratedV2V = $decorated_v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->decoratedV2V->itemsPhpGetPhp(
      // Insert a variable that will later be replaced.
      ['decorated' => DecoUtil::PLACEHOLDER] + $itemsPhp);
  }

}
