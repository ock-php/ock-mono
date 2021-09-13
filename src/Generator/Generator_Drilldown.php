<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Ock\Exception\FormulaToAnythingException;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface;

class Generator_Drilldown implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\Ock\Nursery\NurseryInterface
   */
  private $formulaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownValFormula(Formula_DrilldownValInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromDrilldownFormula(Formula_DrilldownInterface $formula, NurseryInterface $formulaToAnything): self {
    return new self($formula, new V2V_Drilldown_Trivial(), $formulaToAnything);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   */
  protected function __construct(Formula_DrilldownInterface $formula, V2V_DrilldownInterface $v2v, NurseryInterface $formulaToAnything) {
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
