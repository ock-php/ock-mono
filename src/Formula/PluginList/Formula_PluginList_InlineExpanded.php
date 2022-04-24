<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\PluginList;

use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\InlinePluginList\InlinePluginList;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;

/**
 * Default implementation.
 */
class Formula_PluginList_InlineExpanded implements Formula_PluginListInterface {

  /**
   * @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface
   */
  private Formula_PluginListInterface $decorated;

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
   */
  private UniversalAdapterInterface $universalAdapter;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $decorated
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   */
  public function __construct(Formula_PluginListInterface $decorated, UniversalAdapterInterface $universalAdapter) {
    $this->decorated = $decorated;
    $this->incarnator = $universalAdapter;
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
          $this->incarnator);
      }
      catch (IncarnatorException $e) {
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
          Text::builder()
            ->replace('@label', $plugin->getLabel())
            ->replace('@inline_label', $sub_plugin->getLabel())
            ->s('@label: @inline_label'),
          $sub_plugin->getDescription(),
          $sub_plugin->getFormula(),
          []);
      }
    }
    return $plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {

    if (FALSE === strpos($id, '/')) {
      return $this->decorated->idGetPlugin($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $plugin = $this->idGetPlugin($prefix)) {
      return NULL;
    }

    $subtree = PluginListSubtree::fromFormula($plugin->getFormula(), $this->incarnator);

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
