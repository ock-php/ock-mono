<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\V2V\Group\V2V_Group_Partials;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class GroupValFormulaBuilder extends GroupValFormulaBuilderBase {

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface[]
   */
  private array $partials = [];

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   */
  public function __construct(
    private readonly Formula_GroupInterface $groupFormula,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function addPartial(string $key, V2V_GroupInterface $v2v): GroupValFormulaBuilder {
    $this->partials[$key] = $v2v;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupFormula(): Formula_GroupInterface {
    return $this->groupFormula;
  }

  /**
   * {@inheritdoc}
   */
  protected function decorateV2V(?V2V_GroupInterface $v2v): ?V2V_GroupInterface {
    if (!$this->partials) {
      return null;
    }
    return new V2V_Group_Partials(
      $v2v ?? new V2V_Group_Trivial(),
      $this->partials,
    );
  }

}
