<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\Summarizer\Summarizer;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteParameters;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitleMethod;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock_preset\Form\Form_Decorator;
use Drupal\ock_preset\Form\Form_PresetEdit;
use Drupal\ock_preset\Form\Util\PresetConfUtil;
use Drupal\ock_preset\Hub\CfrPluginHub;
use Drupal\ock_preset\RouteHelper\ClassRouteHelper;
use Drupal\ock_preset\Util\StringUtil;
use Drupal\ock_preset\Util\UiUtil;
use Drupal\routelink\RouteModifier\RouteActionLink;
use Drupal\routelink\RouteModifier\RouteDefaultTaskLink;
use Drupal\routelink\RouteModifier\RouteTaskLink;

/**
 * @Route("/admin/structure/ock_preset/{interface}")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("administer ock_preset")
 * @RouteParameters(interface = "ock_preset:interface")
 */
class Controller_IfacePresets extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $interface
   * @param string $methodName
   *
   * @return \Drupal\ock_preset\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($interface, $methodName = 'index') {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
      ],
      $methodName);
  }

  /**
   * @param string $interface
   *
   * @return string
   */
  public function title($interface) {

    return StringUtil::interfaceGenerateLabel($interface);
  }

  /**
   * @Route
   * @RouteDefaultTaskLink("List")
   *
   * @param string $interface
   *
   * @return array
   */
  public function index($interface) {

    $configFactory = \Drupal::configFactory();

    $confKeyPrefix = PresetConfUtil::interfaceConfPrefix($interface);

    $keys = $configFactory->listAll($confKeyPrefix);

    /** @var \Drupal\Core\Config\ImmutableConfig[] $configs */
    $configs = [];
    foreach ($keys as $key) {
      [,,, $machine_name] = explode('.', $key);
      $configs[$machine_name] = $configFactory->get($key);
    }

    $sta = CfrPluginHub::getContainer()->schemaToAnything;
    $schema = CfSchema::iface($interface);
    $summarizer = Summarizer::fromSchema($schema, $sta);

    $rows = [];
    foreach ($configs as $machine_name => $config) {

      $conf = $config->get('conf');
      $summary = (null !== $summarizer)
        ? $summarizer->confGetSummary($conf)
        : '';

      $presetRouteHelper = Controller_Preset::route($interface, $machine_name);

      $row = [];
      $row[] = $config->get('label');
      $row[] = $presetRouteHelper
        ->link($this->t('edit'));
      $row[] = $presetRouteHelper
        ->subpage('delete')
        ->link($this->t('delete'));
      $row[] = Markup::create($summary);

      $rows[] = $row;
    }

    $page = [];

    $interfaceLabel = StringUtil::interfaceGenerateLabel($interface);

    $page['#title'] = $this->t(
      'Manage %type plugin presets.',
      [
        '%type' => $interfaceLabel,
      ]);

    $page['table'] = [
      /* @see \Drupal\Core\Render\Element\Table */
      '#theme' => 'table',
      '#rows' => $rows,
    ];

    return $page;
  }

  /**
   * @Route("/add")
   * @RouteTaskLink("Add preset")
   * @RouteActionLink("Add preset")
   *
   * @param string $interface
   *
   * @return array
   */
  public function add($interface) {
    $page = [];

    $interfaceLabel = StringUtil::interfaceGenerateLabel($interface);

    $page['#title'] = $this->t(
      'Create %type plugin preset',
      ['%type' => $interfaceLabel]);

    $formObject = Form_PresetEdit::create($interface);

    if (!empty($_GET['conf'])) {
      $formObject = $formObject->withConf($_GET['conf']);
    }
    else {
      $conf = NULL;
    }

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $page['form'] = \Drupal::formBuilder()->getForm(
      Form_Decorator::class, $formObject);

    return $page;
  }

}
