<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VDecoratorBase extends Formula_Group_V2VBase {

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $decoratedFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decoratedV2V
   */
  public function __construct(
    private readonly Formula_GroupInterface $decoratedFormula,
    private readonly V2V_GroupInterface $decoratedV2V
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {
    return $this->decoratedFormula->getItemFormulas();
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->decoratedFormula->getLabels();
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->decoratedV2V->itemsPhpGetPhp($itemsPhp);
  }

}
