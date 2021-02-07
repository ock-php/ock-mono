<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\Formula\SequenceVal\Formula_SequenceValInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;
use Donquixote\OCUI\Zoo\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface;

class Generator_Sequence implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Generator\GeneratorInterface
   */
  private $itemGenerator;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createFromSequenceFormula(Formula_SequenceInterface $schema, FormulaToAnythingInterface $schemaToAnything): ?Generator_Sequence {
    return self::create($schema, new V2V_Sequence_Trivial(), $schemaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\SequenceVal\Formula_SequenceValInterface $schema
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createFromSequenceValFormula(Formula_SequenceValInterface $schema, FormulaToAnythingInterface $schemaToAnything): ?Generator_Sequence {
    return self::create($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $schema
   * @param \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  private static function create(Formula_SequenceInterface $schema, V2V_SequenceInterface $v2v, FormulaToAnythingInterface $schemaToAnything): ?Generator_Sequence {

    $itemGenerator = Generator::fromFormula(
      $schema->getItemFormula(),
      $schemaToAnything
    );

    if (NULL === $itemGenerator) {
      return NULL;
    }

    return new self($itemGenerator, $v2v);
  }

  /**
   * @param \Donquixote\OCUI\Generator\GeneratorInterface $itemGenerator
   * @param \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(GeneratorInterface $itemGenerator, V2V_SequenceInterface $v2v) {
    $this->itemGenerator = $itemGenerator;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      return PhpUtil::incompatibleConfiguration("Configuration must be an array or NULL.");
    }

    $phpStatements = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return PhpUtil::incompatibleConfiguration("Sequence array keys must be non-negative integers.");
      }

      $phpStatements[] = $this->itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
