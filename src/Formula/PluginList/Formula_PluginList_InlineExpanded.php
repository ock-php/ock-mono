<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\PluginList;

use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\InlinePluginList\InlinePluginList;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;

/**
 * Default implementation.
 */
class Formula_PluginList_InlineExpanded implements Formula_PluginListInterface {

  /**
   * @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface
   */
  private Formula_PluginListInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Nursery\NurseryInterface
   */
  private NurseryInterface $helper;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $decorated
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $helper
   */
  public function __construct(Formula_PluginListInterface $decorated, NurseryInterface $helper) {
    $this->decorated = $decorated;
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugins(): array {
    $plugins = $this->decorated->getPlugins();
    foreach ($plugins as $prefix => $plugin) {
      if (!$plugin->is('inline')) {
        continue;
      }
      try {
        $subtree = InlinePluginList::fromFormula(
          $plugin->getFormula(),
          $this->helper);
      }
      catch (FormulaToAnythingException $e) {
        // @todo Log this.
        continue;
      }
      if ($subtree === NULL) {
        continue;
      }
      foreach ($subtree->getIds() as $suffix) {
        $sub_plugin = $subtree->idGetPlugin($suffix);
        if ($sub_plugin === NULL) {
          continue;
        }
        $plugins["$prefix/$suffix"] = new Plugin(
          Text::s('@label: @inline_label', [
            '@label' => $plugin->getLabel(),
            '@inline_label' => $sub_plugin->getLabel(),
          ]),
          $sub_plugin->getDescription(),
          $sub_plugin->getFormula(),
          []);
      }
    }
    return $plugins;
  }

  public function idGetPlugin(string $id): ?Plugin {

    if (FALSE === strpos($id, '/')) {
      return $this->decorated->idGetPlugin($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $plugin = $this->idGetPlugin($prefix)) {
      return NULL;
    }

    $subtree = PluginListSubtree::fromFormula($plugin->getFormula(), $this->helper);

    $subFormula = $subtree->getFormula();

    return $subFormula->idIsKnown($suffix);
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->decorated->allowsNull();
  }

}
