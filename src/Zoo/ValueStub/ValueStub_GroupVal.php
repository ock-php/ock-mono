<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStub;

use Donquixote\OCUI\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

class ValueStub_GroupVal implements ValueStubInterface {

  /**
   * @var \Donquixote\OCUI\Zoo\ValueStub\ValueStub_Group
   */
  private $group;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @SCTA
   *
   * @param \Donquixote\OCUI\Schema\GroupVal\CfSchema_GroupValInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupValInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStubInterface {
    return new self(
      ValueStub_Group::createFromGroupSchema($schema->getDecorated(), $conf, $scta),
      $schema->getV2V());
  }

  /**
   * @param \Donquixote\OCUI\Zoo\ValueStub\ValueStub_Group $group
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(ValueStub_Group $group, V2V_GroupInterface $v2v) {
    $this->group = $group;
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\OCUI\Zoo\ValueStub\ValueStub_Group
   */
  public function getGroup(): ValueStub_Group {
    return $this->group;
  }

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2v(): V2V_GroupInterface {
    return $this->v2v;
  }
}
