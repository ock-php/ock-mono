<?php
declare(strict_types=1);

namespace Drupal\ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Incarnator\Incarnator_FormulaReplacerBase;

/**
 * @STA
 */
class Incarnator_PluginListX extends Incarnator_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_PluginListInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $formula */
    return (new Formula_Drilldown(
      new Formula_Select_FromPlugins($formula->getPlugins()),
      new IdToFormula_FromPlugins($formula->getPlugins()),
      $formula->allowsNull()))
    ;
  }

}
