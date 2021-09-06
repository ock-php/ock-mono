<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\PluginList;

use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Plugin\Plugin;

/**
 * Default implementation.
 */
class Formula_PluginList_Adapters implements Formula_PluginListInterface {

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
    $adapterss = [];
    foreach ($plugins as $id => $plugin) {
      if (!$plugin->is('adapter')) {
        continue;
      }
      $adapter_info = AdapterInfo::fromFormula($plugin->getFormula(), $this->helper);
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
