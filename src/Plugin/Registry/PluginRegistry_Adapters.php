<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\Formula_Group;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Formula\Iface\Formula_IfaceInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;

class PluginRegistry_Adapters implements PluginRegistryInterface {

  /**
   * @var \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  private PluginRegistryInterface $decorated;

  /**
   * @var \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  private PluginRegistryInterface $adaptersRegistry;

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $incarnator;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $decorated
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $adaptersRegistry
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   */
  public function __construct(PluginRegistryInterface $decorated, PluginRegistryInterface $adaptersRegistry, IncarnatorInterface $incarnator) {
    $this->decorated = $decorated;
    $this->adaptersRegistry = $adaptersRegistry;
    $this->incarnator = $incarnator;
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
        try {
          $derived = Formula::replace($formula, $this->incarnator);
        }
        catch (IncarnatorException $e) {
          throw new PluginListException($e->getMessage(), 0, $e);
        }
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
            Text::label(
              $adapter_plugin->getLabel(),
              $source_plugin->getLabel()),
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
