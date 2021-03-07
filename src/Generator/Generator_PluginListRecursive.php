<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;

class Generator_PluginListRecursive implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface
   */
  private $formula;

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   */
  private $formulaToAnything;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self
   */
  public static function createFromPluginListFormula(Formula_PluginListInterface $formula, FormulaToAnythingInterface $formulaToAnything): self {
    return new self($formula, $formulaToAnything);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  protected function __construct(Formula_PluginListInterface $formula, FormulaToAnythingInterface $formulaToAnything) {
    $this->formula = $formula;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $combined_id = $conf['plugin'] ?? NULL;

    if ($combined_id === NULL) {
      if ($this->formula->allowsNull()) {
        return 'NULL';
      }

      return PhpUtil::incompatibleConfiguration("Plugin is required.");
    }

    unset($conf['plugin']);

    $id_parts = explode('/', $combined_id);

    $formula = $this->formula;
    foreach ($id_parts as $id_part) {
      $plugins = $formula->getPlugins();
      $plugin = $plugins[$id_part] ?? NULL;
    }

    $id = $id_parts[0];
    $id_remaining = $id_parts[1] ?? NULL;

    $plugins = $this->formula->getPlugins();
    $plugin = $plugins[$combined_id] ?? NULL;

    if ($plugin === NULL) {
      return PhpUtil::incompatibleConfiguration("No plugin found with id '$combined_id'.");
    }

    $subGenerator = Generator::fromFormula(
      $plugin->getFormula(),
      $this->formulaToAnything);

    if (NULL === $subGenerator) {
      return PhpUtil::unsupportedFormula(
        $plugin->getFormula(),
        "No generator could be created for plugin '$combined_id'.");
    }

    return $subGenerator->confGetPhp($conf ?? NULL);
  }
}
