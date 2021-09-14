<?php

declare(strict_types=1);

namespace Donquixote\Ock\ValueStub;

use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

class ValueStub_GroupVal implements ValueStubInterface {

  /**
   * @var \Donquixote\Ock\ValueStub\ValueStub_Group
   */
  private $group;

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @SCTA
   *
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\ValueStub\ValueStubInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_GroupValInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStubInterface {
    return new self(
      ValueStub_Group::createFromGroupFormula($formula->getDecorated(), $conf, $scta),
      $formula->getV2V());
  }

  /**
   * @param \Donquixote\Ock\ValueStub\ValueStub_Group $group
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(ValueStub_Group $group, V2V_GroupInterface $v2v) {
    $this->group = $group;
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\Ock\ValueStub\ValueStub_Group
   */
  public function getGroup(): ValueStub_Group {
    return $this->group;
  }

  /**
   * @return \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  public function getV2v(): V2V_GroupInterface {
    return $this->v2v;
  }

}
