<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\OCUI\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

class Generator_MoreArgs extends Generator_DecoratorBase {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface[]
   */
  private $moreGenerators;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var string[]|null
   */
  private $commonValuesPhp;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function createFromMoreArgsSchema(CfSchema_MoreArgsInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?Generator_MoreArgs {
    return self::create($schema, new V2V_Group_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Schema\MoreArgsVal\CfSchema_MoreArgsValInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
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
   * @param \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface $moreArgsSchema
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
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
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $decorated
   * @param \Donquixote\OCUI\Generator\GeneratorInterface[] $moreGenerators
   * @param string|int $specialKey
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $v2v
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

    return $this->v2v->itemsPhpGetPhp($valuesPhp);
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
