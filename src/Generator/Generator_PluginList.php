<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Exception\GeneratorException_UnsupportedConfiguration;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\Ock\Util\MessageUtil;

class Generator_PluginList implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
   */
  private $universalAdapter;

  /**
   * @param \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self
   */
  #[Adapter]
  public static function createFromPluginListFormula(Formula_PluginListInterface $formula, UniversalAdapterInterface $universalAdapter): self {
    return new self($formula, $universalAdapter);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   */
  protected function __construct(Formula_PluginListInterface $formula, UniversalAdapterInterface $universalAdapter) {
    $this->formula = $formula;
    $this->incarnator = $universalAdapter;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if ($conf === NULL) {
      $conf = [];
    }
    elseif (!is_array($conf)) {
      throw new GeneratorException_IncompatibleConfiguration(
        sprintf(
          'Expected an array, but found %s.',
          MessageUtil::formatValue($conf)));
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

      throw new GeneratorException_IncompatibleConfiguration(
        "Plugin is required.");
    }

    $plugins = $this->formula->getPlugins();
    $plugin = $plugins[$id] ?? NULL;

    if ($plugin === NULL) {
      throw new GeneratorException_IncompatibleConfiguration(
        "No plugin found with id '$id'.");
    }

    try {
      $subGenerator = Generator::fromFormula(
        $plugin->getFormula(),
        $this->incarnator);
    }
    catch (IncarnatorException $e) {
      throw new GeneratorException_UnsupportedConfiguration(
        sprintf(
          'Unsupported plugin id %s: %s',
          var_export($id, TRUE),
          $e->getMessage()),
        0,
        $e);
    }

    return $subGenerator->confGetPhp($conf ?? NULL);
  }

}
