<?php
declare(strict_types=1);

namespace Drupal\cu\Incarnator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Donquixote\ObCK\Incarnator\Incarnator_FormulaReplacerBase;

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
    /** @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $formula */
    return (new Formula_Drilldown(
      new Formula_Select_FromPlugins($formula->getPlugins()),
      new IdToFormula_FromPlugins($formula->getPlugins()),
      $formula->allowsNull()))
    ;
  }

}
