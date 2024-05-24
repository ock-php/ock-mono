<?php

declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\ock\DrupalText;
use Drupal\ock\Formula\DrupalPluginSettings\Formula_DrupalPluginSettings;
use Drupal\ock\Util\DrupalPhpUtil;
use Ock\CodegenTools\Util\CodeGen;
use Ock\CodegenTools\Util\PhpUtil;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Group\Formula_Group;
use Ock\Ock\Formula\Group\Item\GroupFormulaItem;
use Ock\Ock\Formula\Sequence\Formula_Sequence;
use Ock\Ock\Text\Text;

class Formula_LayoutAndRegions {

  /**
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layoutManager
   * @param \Ock\Ock\Core\Formula\FormulaInterface $itemFormula
   * @param callable $callback
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
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
          $sequenceFormula = new Formula_Sequence($itemFormula);
          return new Formula_Group(array_map(
            fn (string|MarkupInterface $drupalRegionLabel) => new GroupFormulaItem(
              DrupalText::fromVar($drupalRegionLabel),
              $sequenceFormula,
            ),
            $definition->getRegionLabels()
          ));
        },
      )
      ->addExpressionPhp(
        'layout',
        CodeGen::phpCallMethod(
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
