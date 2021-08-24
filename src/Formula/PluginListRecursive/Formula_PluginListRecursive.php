<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\PluginListRecursive;

use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;

/**
 * Default implementation.
 */
class Formula_PluginListRecursive implements Formula_PluginListRecursiveInterface {

  /**
   * @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface
   */
  private $pluginList;

  /**
   * @var bool
   */
  private $allowsNull;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $pluginList
   *   Decorated formula.
   * @param bool $allowsNull
   *   TRUE if optional.
   */
  public function __construct(Formula_PluginListInterface $pluginList, bool $allowsNull) {
    $this->pluginList = $pluginList;
    $this->allowsNull = $allowsNull;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginListFormula(): Formula_PluginListInterface {
    return $this->pluginList;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->allowsNull;
  }

}
