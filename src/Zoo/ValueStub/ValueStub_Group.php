<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\ValueStub;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface;

class ValueStub_Group implements ValueStubInterface {

  /**
   * @var array|\Donquixote\Cf\Zoo\ValueStub\ValueStubInterface[]
   */
  private $itemValueStubs;

  /**
   * @SCTA
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStub_Group {
    $itemStubs = self::createItemStubs($schema, $conf, $scta);
    return new self($itemStubs);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Cf\Zoo\ValueStub\ValueStubInterface[]|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  private static function createItemStubs(CfSchema_GroupInterface $groupSchema, $conf, SchemaConfToAnythingInterface $scta): ?array {

    $itemValueStubs = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {

      $itemValueStub = ValueStub::fromSchemaConf($itemSchema, $conf, $scta);

      if (NULL === $itemValueStub) {
        return NULL;
      }

      $itemValueStubs[$k] = $itemValueStub;
    }

    return $itemValueStubs;
  }

  /**
   * @param \Donquixote\Cf\Zoo\ValueStub\ValueStubInterface[] $itemValueStubs
   */
  protected function __construct(array $itemValueStubs) {
    $this->itemValueStubs = $itemValueStubs;
  }

  /**
   * @return array|\Donquixote\Cf\Zoo\ValueStub\ValueStubInterface[]
   */
  public function getItems(): array {
    return $this->itemValueStubs;
  }
}
