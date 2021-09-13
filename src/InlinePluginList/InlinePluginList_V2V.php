<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\V2V\Value\V2V_ValueInterface;

class InlinePluginList_V2V implements InlinePluginListInterface {

  /**
   * @var \Donquixote\Ock\InlinePluginList\InlinePluginListInterface
   */
  private InlinePluginListInterface $decorated;

  /**
   * @var \Donquixote\Ock\V2V\Value\V2V_ValueInterface
   */
  private V2V_ValueInterface $v2v;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\InlinePluginList\InlinePluginListInterface $decorated
   * @param \Donquixote\Ock\V2V\Value\V2V_ValueInterface $v2v
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
