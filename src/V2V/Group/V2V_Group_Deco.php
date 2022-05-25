<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Group;

use Donquixote\Ock\Util\DecoUtil;

class V2V_Group_Deco implements V2V_GroupInterface {

  /**
   * Static factory.
   *
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface|null $decorated
   *
   * @return self
   */
  public static function create(?V2V_GroupInterface $decorated): self {
    return new self($decorated ?? new V2V_Group_Trivial());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decorated
   */
  public function __construct(
    private readonly V2V_GroupInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->decorated->itemsPhpGetPhp(
      // Insert a variable that will later be replaced.
      ['decorated' => DecoUtil::PLACEHOLDER] + $itemsPhp);
  }

}
