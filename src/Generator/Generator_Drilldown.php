<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

class Generator_Drilldown implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownValSchema(CfSchema_DrilldownValInterface $schema, SchemaToAnythingInterface $schemaToAnything): self {
    return new self($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownSchema(CfSchema_DrilldownInterface $schema, SchemaToAnythingInterface $schemaToAnything): self {
    return new self($schema, new V2V_Drilldown_Trivial(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  protected function __construct(CfSchema_DrilldownInterface $schema, V2V_DrilldownInterface $v2v, SchemaToAnythingInterface $schemaToAnything) {
    $this->schema = $schema;
    $this->v2v = $v2v;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    list($id, $subConf) = DrilldownKeysHelper::fromSchema($this->schema)
      ->unpack($conf);

    if (NULL === $id) {
      throw new EvaluatorException_IncompatibleConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $this->schema->getIdToSchema()->idGetSchema($id)) {
      throw new EvaluatorException_IncompatibleConfiguration("Unknown id '$id' in drilldown.");
    }

    $subGenerator = Generator::fromSchema($subSchema, $this->schemaToAnything);

    if (NULL === $subGenerator) {
      throw new EvaluatorException_UnsupportedSchema("Unsupported schema for id '$id' in drilldown.");
    }

    $subValue = $subGenerator->confGetValue($subConf);

    return $this->v2v->idValueGetValue($id, $subValue);
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
