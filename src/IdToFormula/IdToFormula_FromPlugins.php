<?php
declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Plugin\Plugin;

class IdToFormula_FromPlugins implements IdToFormulaInterface {

  /**
   * @var \Donquixote\Ock\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Plugin[] $plugins
   */
  public function __construct(array $plugins) {
    self::validatePlugins(...array_values($plugins));
    $this->plugins = $plugins;
  }

  /**
   * @param \Donquixote\Ock\Plugin\Plugin ...$plugins
   */
  private static function validatePlugins(Plugin ...$plugins): void {}

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    if (!isset($this->plugins[$id])) {
      return NULL;
    }

    return $this->plugins[$id]->getFormula();
  }
}
