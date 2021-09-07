<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

class V2V_Group_Rekey implements V2V_GroupInterface {

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private V2V_GroupInterface $decorated;

  /**
   * @var string[]
   */
  private array $keys;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $decorated
   * @param string[] $keys
   */
  public function __construct(V2V_GroupInterface $decorated, array $keys) {
    $this->decorated = $decorated;
    $this->keys = $keys;
  }

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
