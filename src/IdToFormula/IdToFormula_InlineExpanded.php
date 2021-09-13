<?php
declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\InlineDrilldown\InlineDrilldown;

class IdToFormula_InlineExpanded implements IdToFormulaInterface {

  /**
   * @var \Donquixote\Ock\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $helper;

  /**
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $helper
   */
  public function __construct(IdToFormulaInterface $decorated, IncarnatorInterface $helper) {
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
    catch (IncarnatorException $e) {
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
