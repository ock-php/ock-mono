<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteActionLink;
use Drupal\ock\Attribute\Routing\RouteDefaultTaskLink;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteParameters;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTaskLink;
use Drupal\ock\Attribute\Routing\RouteTitleMethod;
use Drupal\ock\UI\Controller\ControllerRouteNameInterface;
use Drupal\ock\UI\Controller\ControllerRouteNameTrait;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiUtil;
use Drupal\ock_preset\Form\Form_Decorator;
use Drupal\ock_preset\Form\Form_PresetEdit;
use Drupal\ock_preset\Form\Util\PresetConfUtil;
use Ock\Ock\Summarizer\Summarizer;

#[Route('/admin/structure/ock_preset/{interface}')]
#[RouteIsAdmin]
#[RouteTitleMethod([self::class, 'title'])]
#[RouteRequirePermission('administer ock_preset')]
#[RouteParameters(['interface' => 'ock_preset::interface'])]
class Controller_IfacePresets extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $interface
   * @param string $methodName
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(string $interface, string $methodName = 'index'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
      ],
      $methodName,
    );
  }

  /**
   * @param string $interface
   *
   * @return string
   */
  public function title(string $interface): string {
    return StringUtil::interfaceGenerateLabel($interface);
  }

  /**
   * @param string $interface
   *
   * @return array
   */
  #[Route]
  #[RouteDefaultTaskLink('List')]
  public function index(string $interface): array {

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
   * Shows a page with a preset add form.
   *
   * @param string $interface
   *   Interface from url.
   *
   * @return array
   *   Page content render element.
   */
  #[Route('/add')]
  #[RouteTaskLink('Add preset')]
  #[RouteActionLink('Add preset')]
  public function add(string $interface): array {
    $page = [];
    $interfaceLabel = StringUtil::interfaceGenerateLabel($interface);
    $page['#title'] = $this->t(
      'Create %type plugin preset',
      ['%type' => $interfaceLabel],
    );
    $formObject = Form_PresetEdit::create($interface);
    if (!empty($_GET['conf'])) {
      $formObject = $formObject->withConf($_GET['conf']);
    }
    else {
      $conf = NULL;
    }
    $page['form'] = \Drupal::formBuilder()->getForm(
      Form_Decorator::class, $formObject);

    return $page;
  }

}
