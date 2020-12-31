<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\Cf\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class Generator_MoreArgs extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Generator\GeneratorInterface[]
   */
  private $moreGenerators;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var string[]|null
   */
  private $commonValuesPhp;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromMoreArgsSchema(CfSchema_MoreArgsInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_MoreArgs {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function createFromMoreArgsValSchema(
    CfSchema_MoreArgsValInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Generator_MoreArgs {
    return self::create(
      $schema->getDecorated(),
      $schema->getV2V(),
      $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $moreArgsSchema
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function create(
    CfSchema_MoreArgsInterface $moreArgsSchema,
    V2V_GroupInterface $v2v,
    SchemaToAnythingInterface $schemaToAnything
  ): ?Generator_MoreArgs {

    $decoratedGenerator = Generator::fromSchema(
      $moreArgsSchema->getDecorated(),
      $schemaToAnything);

    if (NULL === $decoratedGenerator) {
      return NULL;
    }

    $moreGenerators = [];
    foreach ($moreArgsSchema->getMoreArgs() as $k => $itemSchema) {
      $itemGenerator = Generator::fromSchema($itemSchema, $schemaToAnything);
      if (NULL === $itemGenerator) {
        return NULL;
      }
      $moreGenerators[$k] = $itemGenerator;
    }

    return new self(
      $decoratedGenerator,
      $moreGenerators,
      $moreArgsSchema->getSpecialKey(),
      $v2v);
  }

  /**
   * @param \Donquixote\Cf\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Cf\Generator\GeneratorInterface[] $moreGenerators
   * @param string|int $specialKey
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  protected function __construct(
    GeneratorInterface $decorated,
    array $moreGenerators,
    $specialKey,
    V2V_GroupInterface $v2v
  ) {
    parent::__construct($decorated);
    $this->moreGenerators = $moreGenerators;
    $this->specialKey = $specialKey;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $valuesPhp = $this->getCommonValuesPhp();
    $valuesPhp[$this->specialKey] = parent::confGetPhp($conf);

    return $this->v2v->valuesGetValue($valuesPhp);
  }

  /**
   * @return string[]
   */
  private function getCommonValuesPhp(): array {
    return $this->commonValuesPhp
      ?? $this->commonValuesPhp = $this->buildCommonValuesPhp();
  }

  /**
   * @return string[]
   */
  private function buildCommonValuesPhp(): array {

    $commonValuesPhp = [];
    $commonValuesPhp[$this->specialKey] = NULL;
    foreach ($this->moreGenerators as $k => $evaluator) {
      $commonValuesPhp[$k] = $evaluator->confGetPhp(NULL);
    }

    return $commonValuesPhp;
  }
}
