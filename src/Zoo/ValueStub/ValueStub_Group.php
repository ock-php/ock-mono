<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStub;

use Donquixote\OCUI\Formula\Group\CfSchema_GroupInterface;
use Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface;

class ValueStub_Group implements ValueStubInterface {

  /**
   * @var array|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]
   */
  private $itemValueStubs;

  /**
   * @SCTA
   *
   * @param \Donquixote\OCUI\Formula\Group\CfSchema_GroupInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStub_Group {
    $itemStubs = self::createItemStubs($schema, $conf, $scta);
    return new self($itemStubs);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Group\CfSchema_GroupInterface $groupSchema
   * @param mixed $conf
   * @param \Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
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
   * @param \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[] $itemValueStubs
   */
  protected function __construct(array $itemValueStubs) {
    $this->itemValueStubs = $itemValueStubs;
  }

  /**
   * @return array|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]
   */
  public function getItems(): array {
    return $this->itemValueStubs;
  }
}
