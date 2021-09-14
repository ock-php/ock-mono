<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\PluginList;

use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Plugin\Plugin;

/**
 * Default implementation.
 */
class Formula_PluginList_Adapters implements Formula_PluginListInterface {

  /**
   * @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface
   */
  private Formula_PluginListInterface $decorated;

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $incarnator;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $decorated
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   */
  public function __construct(Formula_PluginListInterface $decorated, IncarnatorInterface $incarnator) {
    $this->decorated = $decorated;
    $this->incarnator = $incarnator;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugins(): array {
    $plugins = $this->decorated->getPlugins();
    $adapterss = [];
    foreach ($plugins as $id => $plugin) {
      if (!$plugin->is('adapter')) {
        continue;
      }
      $adapter_info = AdapterInfo::fromFormula($plugin->getFormula(), $this->incarnator);
      if (!$adapter_info) {
        continue;
      }
      $source_type = $adapter_info->getSourceType();
      $adapterss[$source_type][$id] = $adapter_info->getAdapter();
    }

    foreach ($adapterss as $source_type => $adapters) {

      foreach ($adapters as $id => $adapter) {

      }
    }
    return $plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    // TODO: Implement idGetPlugin() method.
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->decorated->allowsNull();
  }

}
