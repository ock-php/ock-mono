<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

abstract class FormulaReplacerPartial_IfaceBase implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $formulas = [];

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_IfaceInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $formula, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$formula instanceof Formula_IfaceInterface) {
      return NULL;
    }

    $k = $formula->getCacheId();

    return array_key_exists($k, $this->formulas)
      ? $this->formulas[$k]
      : $this->formulas[$k] = $this->formulaDoGetReplacement($formula, $replacer);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $ifaceFormula
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  abstract protected function formulaDoGetReplacement(
    Formula_IfaceInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface;
}
