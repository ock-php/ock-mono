<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteParameters;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitleMethod;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ock_preset\Form\Form_Decorator;
use Drupal\ock_preset\Form\Form_PresetDelete;
use Drupal\ock_preset\Form\Form_PresetEdit;
use Drupal\ock_preset\Form\Util\PresetConfUtil;
use Drupal\ock_preset\RouteHelper\ClassRouteHelper;
use Drupal\ock_preset\Util\UiUtil;
use Drupal\routelink\RouteModifier\RouteDefaultTaskLink;
use Drupal\routelink\RouteModifier\RouteTaskLink;

/**
 * @Route("/admin/structure/ock_preset/{interface}/preset/{preset_name}")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("administer ock_preset")
 * @RouteParameters(interface = "ock_preset:interface")
 */
class Controller_Preset extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @param string $interface
   * @param string $presetName
   * @param string $methodName
   *
   * @return \Drupal\ock_preset\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($interface, $presetName, $methodName = 'edit') {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
        'preset_name' => $presetName,
      ],
      $methodName);
  }

  /**
   * @param string $interface
   * @param string $preset_name
   *
   * @return string
   */
  public function title($interface, $preset_name) {

    $config = $this->getConfig($interface, $preset_name);

    if ($config->isNew()) {
      return $this->t('Unknown preset.');
    }

    return $config->get('label');
  }

  /**
   * @Route
   * @RouteDefaultTaskLink("Edit")
   *
   * @param string $interface
   * @param string $preset_name
   *
   * @return array
   */
  public function edit($interface, $preset_name) {

    $config = $this->getConfig($interface, $preset_name);

    if ($config->isNew()) {
      return [
        '#markup' => $this->t('Unknown preset.'),
      ];
    }

    $page = [];

    $formObject = Form_PresetEdit::create($interface)
      ->withExistingPreset(
        $preset_name,
        $config->get('label'),
        $config->get('conf'));

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $page['form'] = $this->formBuilder()
      ->getForm(
        Form_Decorator::class,
        $formObject);

    return $page;
  }

  /**
   * @Route("/delete")
   * @RouteTaskLink("Delete")
   *
   * @param string $interface
   * @param string $preset_name
   *
   * @return array
   */
  public function delete($interface, $preset_name) {

    $page = [];

    $config = $this->getConfig($interface, $preset_name);

    if ($config->isNew()) {
      return [
        '#markup' => $this->t('Unknown preset.'),
      ];
    }

    $formObject = new Form_PresetDelete(
      $interface,
      $preset_name,
      $config->get('label'),
      $config->get('conf'));

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $page['form'] = $this->formBuilder()
      ->getForm(
        Form_Decorator::class,
        $formObject);

    return $page;
  }

  /**
   * @param string $interface
   * @param string $preset
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   */
  private function getConfig($interface, $preset) {

    $configFactory = \Drupal::configFactory();

    $key = PresetConfUtil::presetConfKey($interface, $preset);

    return $configFactory->get($key);
  }

}
