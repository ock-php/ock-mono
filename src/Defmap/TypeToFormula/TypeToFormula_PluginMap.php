<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginList;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_PluginMap implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap) {
    $this->pluginMap = $pluginMap;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $orNull): FormulaInterface {
    $plugins = $this->pluginMap->typeGetPlugins($type);
    return new Formula_PluginList($plugins, $orNull);
  }

}
