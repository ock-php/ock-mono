<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class Generator_Group implements GeneratorInterface {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface[]
   */
  private $itemGenerators;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromGroupSchema(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_Group {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromGroupValSchema(CfSchema_GroupValInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_Group {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(CfSchema_GroupInterface $groupSchema, V2V_GroupInterface $v2v, SchemaToAnythingInterface $schemaToAnything): ?Generator_Group {

    $itemGenerators = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {
      $itemGenerator = Generator::fromSchema($itemSchema, $schemaToAnything);
      if (NULL === $itemGenerator) {
        return NULL;
      }
      $itemGenerators[$k] = $itemGenerator;
    }

    return new self($itemGenerators, $v2v);
  }

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface[] $itemGenerators
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(array $itemGenerators, V2V_GroupInterface $v2v) {
    $this->itemGenerators = $itemGenerators;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $phpStatements = [];
    foreach ($this->itemGenerators as $key => $itemGenerator) {

      $itemConf = $conf[$key] ?? null;

      $phpStatements[$key] = $itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
