<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VDecoratorBase extends Formula_Group_V2VBase {

  /**
   * @var \Donquixote\ObCK\Formula\Group\Formula_GroupInterface
   */
  private $decoratedFormula;

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $decoratedV2V;

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $decoratedFormula
   * @param \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface $decoratedV2V
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
