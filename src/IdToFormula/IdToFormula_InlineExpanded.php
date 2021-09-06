<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\InlineDrilldown\InlineDrilldown;

class IdToFormula_InlineExpanded implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Nursery\NurseryInterface
   */
  private NurseryInterface $helper;

  /**
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $helper
   */
  public function __construct(IdToFormulaInterface $decorated, NurseryInterface $helper) {
    $this->decorated = $decorated;
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string $id): ?FormulaInterface {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idGetFormula($id);
    }

    list($prefix, $suffix) = explode('/', $id, 2);

    if (NULL === $rawNestedFormula = $this->decorated->idGetFormula($prefix)) {
      return NULL;
    }

    try {
      $subtree = InlineDrilldown::fromFormula(
        $rawNestedFormula,
        $this->helper);
    }
    catch (FormulaToAnythingException $e) {
      // @todo Log this.
      return NULL;
    }

    if ($subtree === NULL) {
      return NULL;
    }

    $formula = $subtree->idGetFormula($suffix);

    return $formula;
  }

}
