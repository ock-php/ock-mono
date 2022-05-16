<?php

declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;

class IdToFormula_Replace implements IdToFormulaInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   */
  public function __construct(
    private IdToFormulaInterface $decorated,
    private UniversalAdapterInterface $universalAdapter,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string|int $id): ?FormulaInterface {
    $formula = $this->decorated->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    try {
      return Formula::replace($formula, $this->universalAdapter);
    }
    catch (IncarnatorException $e) {
      // @todo Log this.
      return NULL;
    }
  }

}
