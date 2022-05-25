<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Group;

class V2V_Group_Partials implements V2V_GroupInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decorated
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface[] $partials
   */
  public function __construct(
    private readonly V2V_GroupInterface $decorated,
    private readonly array $partials,
  ) {}

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
