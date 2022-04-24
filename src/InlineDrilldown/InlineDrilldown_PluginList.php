<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;

class InlineDrilldown_PluginList implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface
   */
  private Formula_PluginListInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $formula
   */
  public function __construct(Formula_PluginListInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdFormula(): Formula_IdInterface {
    return new Formula_Select_FromPlugins(
      $this->formula->getPlugins());
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string|int $id): ?FormulaInterface {
    $plugin = $this->formula->idGetPlugin($id);
    if ($plugin === NULL) {
      return NULL;
    }
    return $plugin->getFormula();
  }

}
