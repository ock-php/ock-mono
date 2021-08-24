<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Plugin\Plugin;

class IdToFormula_FromPlugins implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Plugin[] $plugins
   */
  public function __construct(array $plugins) {
    self::validatePlugins(...array_values($plugins));
    $this->plugins = $plugins;
  }

  /**
   * @param \Donquixote\ObCK\Plugin\Plugin ...$plugins
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
