<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Plugin\Plugin;

class IdToFormula_FromPluginsDecorated implements IdToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Plugin[] $plugins
   */
  public function __construct(array $plugins) {
    self::validatePlugins(...array_values($plugins));
    $this->plugins = $plugins;
  }

  /**
   * @param \Donquixote\OCUI\Plugin\Plugin ...$plugins
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
