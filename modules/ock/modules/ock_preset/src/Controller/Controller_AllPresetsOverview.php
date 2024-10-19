<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteMenuLink;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTitle;
use Drupal\ock\UI\Controller\ControllerRouteNameInterface;
use Drupal\ock\UI\Controller\ControllerRouteNameTrait;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock_preset\Crud\PresetRepository;

#[Route('/admin/structure/ock_preset')]
#[RouteIsAdmin]
#[RouteRequirePermission('administer ock_preset')]
class Controller_AllPresetsOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $methodName
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(string $methodName = 'index'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(self::class, [], $methodName);
  }

  #[Route]
  #[RouteTitle('ock_preset presets')]
  #[RouteMenuLink]
  public function index(): array {
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
          $presets_html .= '<li>'
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
      $cells[] = '<strong>' . $interfaceLink->toString() . '</strong>'
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
