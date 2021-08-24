<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Proxy\Replacer\Formula_Proxy_ReplacerInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Proxy_Replacer implements FormulaReplacerPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_Proxy_ReplacerInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $proxy, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$proxy instanceof Formula_Proxy_ReplacerInterface) {
      return NULL;
    }

    return $proxy->replacerGetFormula($replacer);
  }
}
