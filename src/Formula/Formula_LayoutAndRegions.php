<?php

declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\Formula_Group_SameItemFormula;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Util\PhpUtil;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\DrupalText;
use Drupal\ock\Formula\DrupalPluginSettings\Formula_DrupalPluginSettings;
use Drupal\ock\Util\DrupalPhpUtil;

class Formula_LayoutAndRegions {

  /**
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $itemFormula
   * @param callable $callback
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function create(
    LayoutPluginManagerInterface $layoutManager,
    FormulaInterface $itemFormula,
    callable $callback,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'layout_id',
        Text::t('Layout'),
        new Formula_LayoutId($layoutManager),
      )
      ->addDynamicFormula(
        'layout_settings',
        Text::t('Layout settings'),
        ['layout_id'],
        function (string $layoutId) use ($layoutManager): FormulaInterface {
          $layout = $layoutManager->createInstance($layoutId);
          return new Formula_DrupalPluginSettings($layout);
        },
      )
      ->addDynamicFormula(
        'regions',
        Text::t('Regions'),
        ['layout_id'],
        function (string $layoutId) use (
          $layoutManager,
          $itemFormula,
        ): FormulaInterface {
          $definition = $layoutManager->getDefinition($layoutId, FALSE);
          // @todo Dedicated formula with regions tabledrag!
          return new Formula_Group_SameItemFormula(
            new Formula_Sequence($itemFormula),
            // @todo Report fails.
            DrupalText::multiple($definition->getRegionLabels()),
          );
        },
      )
      ->addExpression(
        'layout',
        PhpUtil::phpCallMethod(
          DrupalPhpUtil::service('plugin.manager.core.layout'),
          'createInstance',
          [
            PhpUtil::phpPlaceholder('layout_id'),
            PhpUtil::phpPlaceholder('layout_settings'),
          ],
        ),
      )
      ->call($callback, ['layout', 'regions']);
  }

}
