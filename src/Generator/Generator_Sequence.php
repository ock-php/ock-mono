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
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createFromSequenceFormula(Formula_SequenceInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?Generator_Sequence {
    return self::create($formula, new V2V_Sequence_Trivial(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\SequenceVal\Formula_SequenceValInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createFromSequenceValFormula(Formula_SequenceValInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?Generator_Sequence {
    return self::create($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  private static function create(Formula_SequenceInterface $formula, V2V_SequenceInterface $v2v, FormulaToAnythingInterface $formulaToAnything): ?Generator_Sequence {

    $itemGenerator = Generator::fromFormula(
      $formula->getItemFormula(),
      $formulaToAnything
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
      return PhpUtil::expectedConfigButFound("Configuration must be an array or NULL.", $conf);
    }

    $phpStatements = [];
    foreach ($conf as $delta => $itemConf) {

      if ((string) (int) $delta !== (string) $delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return PhpUtil::expectedConfigButFound("Sequence array keys must be non-negative integers.", $conf);
      }

      $phpStatements[] = $this->itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }
}
