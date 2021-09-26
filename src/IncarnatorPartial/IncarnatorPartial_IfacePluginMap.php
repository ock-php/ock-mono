<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Iface\Formula_IfaceInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;

/**
 * @_STA
 */
class IncarnatorPartial_IfacePluginMap extends IncarnatorPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap) {
    $this->pluginMap = $pluginMap;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\Iface\Formula_IfaceInterface $formula */
    try {
      $plugins = $this->pluginMap->typeGetPlugins($formula->getInterface());
    }
    catch (PluginListException $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }
    $ff = new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull());
    return $ff;
  }

}
