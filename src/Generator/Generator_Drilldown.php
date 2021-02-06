<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\OCUI\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;
use Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

class Generator_Drilldown implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\DrilldownVal\CfSchema_DrilldownValInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownValSchema(CfSchema_DrilldownValInterface $schema, SchemaToAnythingInterface $schemaToAnything): self {
    return new self($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownSchema(CfSchema_DrilldownInterface $schema, SchemaToAnythingInterface $schemaToAnything): self {
    return new self($schema, new V2V_Drilldown_Trivial(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  protected function __construct(CfSchema_DrilldownInterface $schema, V2V_DrilldownInterface $v2v, SchemaToAnythingInterface $schemaToAnything) {
    $this->schema = $schema;
    $this->v2v = $v2v;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    list($id, $subConf) = DrilldownKeysHelper::fromSchema($this->schema)
      ->unpack($conf);

    $subValuePhp = $this->idConfGetSubValuePhp($id, $subConf);

    return $this->v2v->idPhpGetPhp($id, $subValuePhp);
  }

  /**
   * @param string|int|null $id
   * @param $subConf
   *
   * @return string
   */
  private function idConfGetSubValuePhp($id, $subConf): string {

    if (NULL === $id) {
      return PhpUtil::incompatibleConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $this->schema->getIdToSchema()->idGetSchema($id)) {
      return PhpUtil::incompatibleConfiguration("Unknown id '$id' in drilldown.");
    }

    $subGenerator = Generator::fromSchema($subSchema, $this->schemaToAnything);

    if (NULL === $subGenerator) {
      return PhpUtil::unsupportedSchema($subSchema, "Unsupported schema for id '$id' in drilldown.");
    }

    return $subGenerator->confGetPhp($subConf);
  }
}
