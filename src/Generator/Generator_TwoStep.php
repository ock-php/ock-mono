<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\TwoStepKeysHelper\TwoStepKeysHelper;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface;
use Donquixote\ObCK\Formula\TwoStepVal\Formula_TwoStepValInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\V2V\TwoStep\V2V_TwoStep_Trivial;
use Donquixote\ObCK\V2V\TwoStep\V2V_TwoStepInterface;

class Generator_TwoStep implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface
   */
  private $formula;

  /**
   * @var \Donquixote\ObCK\V2V\TwoStep\V2V_TwoStepInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\ObCK\Nursery\NurseryInterface
   */
  private $formulaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\TwoStepVal\Formula_TwoStepValInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function fromV2V(Formula_TwoStepValInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromTwoStepFormula(Formula_TwoStepInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula, new V2V_TwoStep_Trivial(), $formulaToAnything);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface $formula
   * @param \Donquixote\ObCK\V2V\TwoStep\V2V_TwoStepInterface $v2v
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   */
  protected function __construct(Formula_TwoStepInterface $formula, V2V_TwoStepInterface $v2v, NurseryInterface $formulaToAnything) {
    $this->formula = $formula;
    $this->v2v = $v2v;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    list($id, $subConf) = TwoStepKeysHelper::fromFormula($this->formula)
      ->unpack($conf);

    if (NULL === $id) {
      if ($this->formula->allowsNull()) {
        return 'NULL';
      }

      return PhpUtil::incompatibleConfiguration("Required id for twoStep is missing.");
    }

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


    if (NULL === $subFormula = $this->formula->getIdToFormula()->idGetFormula($id)) {
      return PhpUtil::incompatibleConfiguration("Unknown id '$id' in twoStep.");
    }

    try {
      $subGenerator = Generator::fromFormula($subFormula, $this->formulaToAnything);
    }
    catch (FormulaToAnythingException $e) {
      return PhpUtil::unsupportedFormula($subFormula, "Unsupported formula for id '$id' in twoStep.");
    }

    return $subGenerator->confGetPhp($subConf);
  }

}
