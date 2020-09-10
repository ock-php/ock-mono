<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\ValueStub;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class ValueStub_GroupVal implements ValueStubInterface {

  /**
   * @var \Donquixote\Cf\Zoo\ValueStub\ValueStub_Group
   */
  private $group;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @SCTA
   *
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Cf\Zoo\ValueStub\ValueStubInterface|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupValInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStubInterface {
    return new self(
      ValueStub_Group::createFromGroupSchema($schema->getDecorated(), $conf, $scta),
      $schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Zoo\ValueStub\ValueStub_Group $group
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(ValueStub_Group $group, V2V_GroupInterface $v2v) {
    $this->group = $group;
    $this->v2v = $v2v;
  }

  /**
   * @return \Donquixote\Cf\Zoo\ValueStub\ValueStub_Group
   */
  public function getGroup(): ValueStub_Group {
    return $this->group;
  }

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2v(): V2V_GroupInterface {
    return $this->v2v;
  }
}
