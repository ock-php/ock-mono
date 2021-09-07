<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

class V2V_Group_Partials implements V2V_GroupInterface {

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private V2V_GroupInterface $decorated;

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface[]
   */
  private array $partials;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $decorated
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface[] $partials
   */
  public function __construct(V2V_GroupInterface $decorated, array $partials) {
    $this->decorated = $decorated;
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    foreach ($this->partials as $key => $v2v) {
      $itemsPhp[$key] = $v2v->itemsPhpGetPhp($itemsPhp);
    }
    return $this->decorated->itemsPhpGetPhp($itemsPhp);
  }

}
