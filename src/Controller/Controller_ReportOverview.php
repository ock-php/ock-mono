<?php
declare(strict_types=1);

namespace Drupal\cu\Controller;

use Donquixote\OCUI\Plugin\Map\PluginMapInterface;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitle;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\Exception\UnknownExtensionException;
use Drupal\Core\Render\Markup;
use Drupal\cu\RouteHelper\ClassRouteHelper;
use Drupal\cu\RouteHelper\ClassRouteHelperInterface;
use Drupal\cu\Util\StringUtil;
use Drupal\routelink\RouteModifier\RouteMenuLink;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Route("/admin/reports/cu")
 * @RouteIsAdmin
 * @RouteRequirePermission("view cu report")
 */
class Controller_ReportOverview extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * @return \Drupal\cu\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [],
      'overview');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    /** @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface $plugin_map */
    $plugin_map = $container->get(PluginMapInterface::class);
    return new self($plugin_map);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $plugin_map
   *   Plugin map.
   */
  public function __construct(PluginMapInterface $plugin_map) {
    $this->pluginMap = $plugin_map;
  }

  /**
   * @Route
   * @RouteTitle("cu plugins")
   * @RouteMenuLink
   */
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
        catch (UnknownExtensionException $e) {
          // Ignore.
        }
      }

      $rows[] = [
        [
          'colspan' => 4,
          'data' => ['#markup' => '<h3>' . $module_label . '</h3>'],
        ],
      ];

      foreach ($module_rows as $row) {
        $rows[] = $row;
      }
    }

    return [
      '#header' => [
        t('Human name'),
        t('List'),
        t('Code'),
        t('Interface'),
      ],
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }
}
