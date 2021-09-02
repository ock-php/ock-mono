<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Registry;

use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Group\Formula_Group;
use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\V2V\Group\V2V_Group_Trivial;

class PluginRegistry_Adapters implements PluginRegistryInterface {

  /**
   * @var \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface
   */
  private PluginRegistryInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface
   */
  private PluginRegistryInterface $adaptersRegistry;

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   */
  private FormulaToAnythingInterface $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface $decorated
   * @param \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface $adaptersRegistry
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  public function __construct(PluginRegistryInterface $decorated, PluginRegistryInterface $adaptersRegistry, FormulaToAnythingInterface $formulaToAnything) {
    $this->decorated = $decorated;
    $this->adaptersRegistry = $adaptersRegistry;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = $this->decorated->getPluginss();
    $extended_pluginss = $pluginss;
    foreach ($this->adaptersRegistry->getPluginss() as $type => $plugins) {
      foreach ($plugins as $adapter_id => $adapter_plugin) {
        $formula = $adapter_plugin->getFormula();
        $derived = Formula::replace($formula, $this->formulaToAnything);
        if ($derived instanceof Formula_GroupValInterface) {
          $v2v = $derived->getV2V();
          $derived = $derived->getDecorated();
        }
        elseif ($derived instanceof Formula_GroupInterface) {
          $v2v = new V2V_Group_Trivial();
        }
        else {
          continue;
        }
        $item_formulas = $derived->getItemFormulas();
        $first_item_formula = reset($item_formulas);
        $first_item_key = key($item_formulas);
        if (!$first_item_formula instanceof Formula_IfaceInterface) {
          continue;
        }
        $source_type = $first_item_formula->getInterface();
        foreach ($pluginss[$source_type] as $source_id => $source_plugin) {
          $source_formula = $source_plugin->getFormula();
          $extended_pluginss[$type]["$adapter_id/$source_id"] = new Plugin(
            Text::s('@label: @inline_label', [
              '@label' => $adapter_plugin->getLabel(),
              '@inline_label' => $source_plugin->getLabel(),
            ]),
            $source_plugin->getDescription(),
            new Formula_GroupVal(
              new Formula_Group(
                [$first_item_key => $source_formula] + $item_formulas,
                $derived->getLabels()),
              $v2v),
            []);
        }
      }
    }
    return $extended_pluginss;
  }

}
