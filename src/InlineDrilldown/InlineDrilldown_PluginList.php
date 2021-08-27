<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;

class InlineDrilldown_PluginList implements InlineDrilldownInterface {

  /**
   * @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface
   */
  private Formula_PluginListInterface $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $formula
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
  public function idGetFormula(string $id): ?FormulaInterface {
    $plugin = $this->formula->idGetPlugin($id);
    if ($plugin === NULL) {
      return NULL;
    }
    return $plugin->getFormula();
  }

}
