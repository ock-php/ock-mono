<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\Formula\DrupalPluginSettings\Formula_DrupalPluginSettings;
use Drupal\renderkit\Formula\Formula_LayoutId;

class IdToFormula_Layout implements IdToFormulaInterface {

  /**
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  private $manager;

  /**
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $manager
   */
  public function __construct(LayoutPluginManagerInterface $manager) {
    $this->manager = $manager;
  }

  /**
   * Static factory. Creates a drilldown formula.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(LayoutPluginManagerInterface $layoutManager): FormulaInterface {
    $drilldown = new Formula_Drilldown(
      new Formula_LayoutId($layoutManager),
      new self($layoutManager));
    // Options at the top-level.
    return $drilldown->withKeys('layout_id', 'layout_settings');
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {
    try {
      $plugin = $this->manager->createInstance($id);
    }
    catch (PluginException $e) {
      // @todo Log this?
      return NULL;
    }
    return new Formula_DrupalPluginSettings($plugin);
  }

}
