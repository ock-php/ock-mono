<?php
declare(strict_types=1);

namespace Donquixote\ObCK\ValueStub;

use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

class ValueStub_GroupVal implements ValueStubInterface {

  /**
   * @var \Donquixote\ObCK\ValueStub\ValueStub_Group
   */
  private $group;

  /**
   * @var \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @SCTA
   *
   * @param \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param mixed $conf
   * @param \Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\ObCK\ValueStub\ValueStubInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupValInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStubInterface {
    return new self(
      ValueStub_Group::createFromGroupFormula($formula->getDecorated(), $conf, $scta),
      $formula->getV2V());
  }

  /**
   * @param \Donquixote\ObCK\ValueStub\ValueStub_Group $group
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(ValueStub_Group $group, V2V_GroupInterface $v2v) {
    $this->group = $group;
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\ObCK\ValueStub\ValueStub_Group
   */
  public function getGroup(): ValueStub_Group {
    return $this->group;
  }

  /**
   * @return \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  public function getV2v(): V2V_GroupInterface {
    return $this->v2v;
  }
}
