<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VDecoratorBase extends Formula_Group_V2VBase {

  /**
   * @var \Donquixote\Ock\Formula\Group\Formula_GroupInterface
   */
  private $decoratedFormula;

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $decoratedV2V;

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $decoratedFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $decoratedV2V
   */
  public function __construct(
    Formula_GroupInterface $decoratedFormula,
    V2V_GroupInterface $decoratedV2V
  ) {
    $this->decoratedFormula = $decoratedFormula;
    $this->decoratedV2V = $decoratedV2V;
  }

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
