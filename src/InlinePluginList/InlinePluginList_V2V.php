<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface;

class InlinePluginList_V2V implements InlinePluginListInterface {

  /**
   * @var \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface
   */
  private InlinePluginListInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface
   */
  private V2V_ValueInterface $v2v;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface $decorated
   * @param \Donquixote\ObCK\Zoo\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(InlinePluginListInterface $decorated, V2V_ValueInterface $v2v) {
    $this->decorated = $decorated;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    return $this->decorated->getIds();
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    $plugin = $this->decorated->idGetPlugin($id);
    if ($plugin === NULL) {
      return NULL;
    }
    return $plugin->withFormula(
      new Formula_ValueToValue(
        $plugin->getFormula(),
        $this->v2v));
  }

}
