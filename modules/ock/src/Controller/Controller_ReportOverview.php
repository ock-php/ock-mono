<?php
declare(strict_types=1);

namespace Drupal\ock\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTitle;
use Drupal\controller_attributes\ClassRouteHelper;
use Drupal\controller_attributes\ClassRouteHelperInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\Exception\UnknownExtensionException;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Render\Markup;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\Util\StringUtil;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DrupalTesting\DrupalTesting;
use Ock\Ock\Plugin\Map\PluginMapInterface;

#[Route('/admin/reports/ock')]
#[RouteIsAdmin]
#[RouteRequirePermission('view ock reports')]
class Controller_ReportOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;
  use ContainerInjectionViaAttributesTrait;

  /**
   * @return \Drupal\controller_attributes\ClassRouteHelperInterface
   */
  public static function route(): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [],
      'overview',
    );
  }

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
   * @return array
   *
   * @throws \Ock\Ock\Exception\PluginListException
   */
  #[Route]
  #[RouteTitle('ock plugins')]
  #[RouteMenuLink]
  public function overview(): array {
    $types = $this->pluginMap->getTypes();

    /** @var array[] $rows */
    $rows = [];
    /** @var array[][] $rows_grouped */
    $rows_grouped = [];
    foreach ($types as $interface) {
      $plugins = $this->pluginMap->typeGetPlugins($interface);
      $label = StringUtil::interfaceGenerateLabel($interface);
      $count = $this->t('@n plugin definitions', ['@n' => \count($plugins)]);
      $interface_shortname = StringUtil::classGetShortname($interface);

      $helper = Controller_ReportIface::route($interface);
      $row = [
        $helper->subpage('demo')
          ->link($label),
        $helper->link($count),
        $helper
          ->subpage('code')
          ->link($interface_shortname),
        Markup::create('<code>' . $interface . '</code>'),
      ];

      $fragments = explode('\\', $interface);

      if (1
        && 'Drupal' === $fragments[0]
        && isset($fragments[2])
        # && module_exists($fragments[1])
      ) {
        $rows_grouped[$fragments[1]][] = $row;
      }
      else {
        $rows[] = $row;
      }
    }

    $modules_info = DrupalTesting::service(ModuleExtensionList::class);

    foreach ($rows_grouped as $module => $module_rows) {
      $module_label = $module;
      if ($module !== 'Core' && $module !== 'Component') {
        try {
          $module_label = $modules_info->getName($module);
        }
        catch (UnknownExtensionException) {
          // Ignore.
        }
      }

      $rows[] = [
        [
          'colspan' => 4,
          'data' => ['#markup' => '<h3>' . $module_label . '</h3>'],
        ],
      ];

      $rows = [
        ...$rows,
        ...\array_values($module_rows),
      ];
    }

    return [
      '#header' => [
        $this->t('Human name'),
        $this->t('List'),
        $this->t('Code'),
        $this->t('Interface'),
      ],
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

}
