<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitle;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock_preset\Crud\PresetRepository;
use Drupal\ock_preset\RouteHelper\ClassRouteHelper;
use Drupal\routelink\RouteModifier\RouteMenuLink;

/**
 * @Route("/admin/structure/ock_preset")
 * @RouteIsAdmin
 * @RouteRequirePermission("administer ock_preset")
 */
class Controller_AllPresetsOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $methodName
   *
   * @return \Drupal\ock_preset\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($methodName = 'index') {
    return ClassRouteHelper::fromClassName(self::class, [], $methodName);
  }

  /**
   * @Route
   * @RouteTitle("ock_preset presets")
   * @RouteMenuLink
   *
   * @return array
   */
  public function index() {

    /** @var \Drupal\Core\Config\ImmutableConfig[][] $configss */
    $configss = PresetRepository::create()->loadAll();

    $orphanConfigss = $configss;

    $interfaceLabels = ock_preset()->getInterfaceLabels();

    $rows = [];
    foreach ($interfaceLabels as $interface => $interfaceLabel) {

      $presets_html = '';
      if (isset($configss[$interface])) {
        $interfaceConfigs = $configss[$interface];
        unset($orphanConfigss[$interface]);

        foreach ($interfaceConfigs as $machine_name => $config) {

          $presets_html .= ''
            . '<li>'
            . Controller_Preset::route($interface, $machine_name)
              ->link($config->get('label'))
              ->toString()
            . '</li>';
        }
      }

      if ('' !== $presets_html) {
        $presets_html = '<ul>' . $presets_html . '</ul>';
      }
      else {
        $presets_html = '- ' . $this->t('none') . ' -';
      }

      $interfaceRouteHelper = Controller_IfacePresets::route($interface);

      $interfaceLink = $interfaceRouteHelper
        ->link($this->t($interfaceLabel));

      $addPresetLink = $interfaceRouteHelper
        ->subpage('add')
        ->link($this->t('add preset'));

      $cells = [];
      $cells[] = ''
        . '<strong>' . $interfaceLink->toString() . '</strong>'
        . '<br/>'
        . '<code>' . $interface . '</code>';
      $cells[] = $presets_html;
      $cells[] = '[' . $addPresetLink->toString() . ']';

      $row = [];
      foreach ($cells as $cell) {
        $row[] = Markup::create($cell);
      }

      $rows[] = $row;
    }

    return [
      /* @see theme_table() */
      '#theme' => 'table',
      '#header' => [
        $this->t('Type'),
        $this->t('Stored presets'),
        $this->t('Add preset'),
      ],
      '#rows' => $rows,
    ];
  }

}
