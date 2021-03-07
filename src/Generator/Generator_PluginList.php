<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\PhpUtil;

class Generator_PluginList implements GeneratorInterface {

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

    if ($conf === NULL) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      return PhpUtil::expectedConfigButFound("Configuration must be an array.", $conf);
    }

    // Look for 'decorator' notation.
    $decorators_conf = $conf['decorators'] ?? [];
    unset($conf['decorators']);

    if ($decorators_conf) {
      foreach ($decorators_conf as $decorator_conf) {
        // The 'decorator' notation assumes that the name of the first parameter
        // is 'decorator'.
        $conf = ['decorated' => $conf] + $decorator_conf;
      }
    }

    $id = $conf['plugin'] ?? NULL;
    unset($conf['plugin']);

    // Look for 'adapter' notation, where the id contains one or more slashes.
    if ($id && preg_match('@^(.*?(\w+))/(.+)$@', $id, $m)) {
      [, $id, $param_name, $remaining_id] = $m;
      // The 'adapter' notation assumes that the name of the first parameter is
      // identical to the last part of the plugin id.
      $conf = [$param_name => ['plugin' => $remaining_id] + $conf];
    }

    if ($id === NULL) {
      if ($this->formula->allowsNull()) {
        return 'NULL';
      }

      return PhpUtil::incompatibleConfiguration("Plugin is required.");
    }


    $plugins = $this->formula->getPlugins();
    $plugin = $plugins[$id] ?? NULL;

    if ($plugin === NULL) {
      return PhpUtil::incompatibleConfiguration("No plugin found with id '$id'.");
    }

    $subGenerator = Generator::fromFormula(
      $plugin->getFormula(),
      $this->formulaToAnything);

    if (NULL === $subGenerator) {
      return PhpUtil::unsupportedFormula(
        $plugin->getFormula(),
        "No generator could be created for plugin '$id'.");
    }

    return $subGenerator->confGetPhp($conf ?? NULL);
  }
}
