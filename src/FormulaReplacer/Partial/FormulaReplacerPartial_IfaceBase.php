<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

abstract class FormulaReplacerPartial_IfaceBase implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $formulas = [];

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_IfaceWithContextInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $formula, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$formula instanceof Formula_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $formula->getCacheId();

    return array_key_exists($k, $this->formulas)
      ? $this->formulas[$k]
      : $this->formulas[$k] = $this->formulaDoGetReplacement($formula, $replacer);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  abstract protected function formulaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface;
}
