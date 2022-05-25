<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Group;

class V2V_Group_Rekey implements V2V_GroupInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decorated
   * @param string[] $keys
   */
  public function __construct(
    private readonly V2V_GroupInterface $decorated,
    private readonly array $keys,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    $itemsPhpRekeyed = [];
    foreach ($this->keys as $dest_key => $source_key) {
      $itemsPhpRekeyed[$dest_key] = $itemsPhp[$source_key];
    }
    return $this->decorated->itemsPhpGetPhp($itemsPhpRekeyed);
  }

}
