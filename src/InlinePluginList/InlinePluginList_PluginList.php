<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\ObCK\Plugin\Plugin;

class InlinePluginList_PluginList implements InlinePluginListInterface {

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
  public function getIds(): array {
    return array_keys($this->formula->getPlugins());
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    return $this->formula->idGetPlugin($id);
  }

}
