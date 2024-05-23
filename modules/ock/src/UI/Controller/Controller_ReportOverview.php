<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\Exception\UnknownExtensionException;
use Drupal\Core\Render\Markup;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteMenuLink;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTitle;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Plugin\Map\PluginMapInterface;

#[Route('/admin/reports/ock')]
#[RouteIsAdmin]
#[RouteRequirePermission('view ock reports')]
class Controller_ReportOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;
  use ContainerInjectionViaAttributesTrait;

  /**
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
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

    /** @var \Drupal\Core\Extension\ModuleExtensionList $modules_info */
    $modules_info = \Drupal::service('extension.list.module');

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
