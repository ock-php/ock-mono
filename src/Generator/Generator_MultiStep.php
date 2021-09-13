<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface;
use Donquixote\Ock\Formula\MultiStepVal\Formula_MultiStepValInterface;
use Donquixote\Ock\MultiStepKeysHelper\MultiStepKeysHelper;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;
use Donquixote\Ock\V2V\MultiStep\V2V_MultiStep_Trivial;
use Donquixote\Ock\V2V\MultiStep\V2V_MultiStepInterface;

class Generator_MultiStep implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\Ock\Nursery\NurseryInterface
   */
  private $nursery;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\MultiStepVal\Formula_MultiStepValInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function fromV2V(Formula_MultiStepValInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromMultiStepFormula(Formula_MultiStepInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula, new V2V_Group_Trivial(), $formulaToAnything);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface $formula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   */
  protected function __construct(Formula_MultiStepInterface $formula, V2V_GroupInterface $v2v, NurseryInterface $nursery) {
    $this->formula = $formula;
    $this->v2v = $v2v;
    $this->nursery = $nursery;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $parts = [];
    for ($step = $this->formula; $step; $step = $step->next($step_conf)) {
      $key = $step->getKey();
      $step_conf = $conf[$key] ?? NULL;
      $parts[$key] = Generator::fromFormula(
        $step->getFormula(),
        $this->nursery)
        ->confGetPhp($step_conf);
      $step = $step->next($step_conf);
    }

    return $this->v2v->itemsPhpGetPhp($parts);
  }

}
