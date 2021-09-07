<?php

declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Group\Formula_Group_SameItemFormula;
use Donquixote\ObCK\Formula\Sequence\Formula_Sequence;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Text\Text;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\cu\DrupalText;
use Drupal\cu\Formula\DrupalPluginSettings\Formula_DrupalPluginSettings;
use Drupal\cu\Util\DrupalPhpUtil;
use Drupal\renderkit\Formula\Formula_LayoutId;

class IdToFormula_LayoutAndRegions implements IdToFormulaInterface {

  /**
   * @var callable
   */
  private $callback;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private FormulaInterface $itemFormula;

  /**
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  private LayoutPluginManagerInterface $layoutManager;

  /**
   * Constructor.
   *
   * @param callable $callback
   *   Factory callback with ($layout, $regions) -> *.
   *   Must be a static method.
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $itemFormula
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   */
  public function __construct(callable $callback, FormulaInterface $itemFormula, LayoutPluginManagerInterface $layoutManager) {
    $this->callback = $callback;
    $this->itemFormula = $itemFormula;
    $this->layoutManager = $layoutManager;
  }

  /**
   * Static factory. Creates a drilldown formula.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $itemFormula
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   * @param callable $callback
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(
    FormulaInterface $itemFormula,
    LayoutPluginManagerInterface $layoutManager,
    callable $callback
  ): FormulaInterface {
    $drilldown = new Formula_Drilldown(
      new Formula_LayoutId($layoutManager),
      new self($callback, $itemFormula, $layoutManager));
    // Options at the top-level.
    return $drilldown->withKeys('layout_id', NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string $id): ?FormulaInterface {
    try {
      $definition = $this->layoutManager->getDefinition($id, FALSE);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }
    if (!$definition) {
      return NULL;
    }
    try {
      $layout = $this->layoutManager->createInstance($id);
    }
    catch (PluginException $e) {
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }
    return Formula::group()
      ->addValue(
        'layout_id',
        $id)
      ->add(
        'layout_settings',
        new Formula_DrupalPluginSettings($layout),
        Text::t('Layout settings'))
      ->addValuePhp(
        'layout_manager',
        DrupalPhpUtil::service('plugin.manager.core.layout'))
      ->addObjectMethodCall(
        'layout',
        'layout_manager',
        'createInstance',
        ['layout_id', 'layout_settings'])
      ->addDependentCall(
        'layout',
        [self::class, 'createLayout'],
        ['layout_id', 'layout_settings', 'layout_manager'])
      ->add(
        'regions',
        // @todo Dedicated formula with regions tabledrag!
        new Formula_Group_SameItemFormula(
          new Formula_Sequence($this->itemFormula),
          // @todo Report fails.
          DrupalText::multiple(
            $definition->getRegionLabels())),
        Text::t('Regions'))
      ->call(
        $this->callback,
        ['layout', 'regions']);
  }

}
