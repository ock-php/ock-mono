<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

class Generator_Drilldown implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  private $formula;

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   */
  private $formulaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownValFormula(Formula_DrilldownValInterface $formula, FormulaToAnythingInterface $formulaToAnything): self {
    return new self($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownFormula(Formula_DrilldownInterface $formula, FormulaToAnythingInterface $formulaToAnything): self {
    return new self($formula, new V2V_Drilldown_Trivial(), $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  protected function __construct(Formula_DrilldownInterface $formula, V2V_DrilldownInterface $v2v, FormulaToAnythingInterface $formulaToAnything) {
    $this->formula = $formula;
    $this->v2v = $v2v;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    list($id, $subConf) = DrilldownKeysHelper::fromFormula($this->formula)
      ->unpack($conf);

    if (NULL === $id) {
      if ($this->formula->allowsNull()) {
        return 'NULL';
      }

      return PhpUtil::incompatibleConfiguration("Required id for drilldown is missing.");
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
      return PhpUtil::incompatibleConfiguration("Unknown id '$id' in drilldown.");
    }

    try {
      $subGenerator = Generator::fromFormula($subFormula, $this->formulaToAnything);
    }
    catch (FormulaToAnythingException $e) {
      return PhpUtil::unsupportedFormula($subFormula, "Unsupported formula for id '$id' in drilldown.");
    }

    return $subGenerator->confGetPhp($subConf);
  }

}
