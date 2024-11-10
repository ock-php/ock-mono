<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTitle;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock_preset\Crud\PresetRepository;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Plugin\Map\PluginMapInterface;

#[Route('/admin/structure/ock_preset')]
#[RouteIsAdmin]
#[RouteRequirePermission('administer ock_preset')]
class Controller_AllPresetsOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Plugin\Map\PluginMapInterface $pluginMap
   *   Plugin map.
   */
  public function __construct(
    #[GetService]
    private readonly PluginMapInterface $pluginMap,
  ) {}

  /**
   * Gets a builder object to create urls and links.
   *
   * @param string $methodName
   *   Name of a method in this class.
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   *   Builder object to create links and urls.
   */
  public static function route(string $methodName = 'index'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(self::class, [], $methodName);
  }

  /**
   * Shows a page with an overview list of all types with their presets.
   *
   * @return array
   *   Page content render element.
   *
   * @throws \Ock\Ock\Exception\PluginListException
   *   The list of plugins types cannot be retrieved.
   */
  #[Route]
  #[RouteTitle('ock_preset presets')]
  #[RouteMenuLink]
  public function index(): array {
    $types = $this->pluginMap->getTypes();
    $configss = PresetRepository::create()->loadAll();
    $orphanConfigss = $configss;

    $rows = [];
    foreach ($types as $interface) {
      $interfaceLabel = StringUtil::interfaceGenerateLabel($interface);

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
