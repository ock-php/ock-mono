<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\Component\Render\MarkupInterface;
use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteDefaultTaskLink;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTaskLink;
use Drupal\controller_attributes\Attribute\RouteTitleMethod;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\ock\UI\ParamConverter\ParamConverter_Iface;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\UiUtil;
use Drupal\ock_preset\Form\Form_Decorator;
use Drupal\ock_preset\Form\Form_PresetDelete;
use Drupal\ock_preset\Form\Form_PresetEdit;
use Drupal\ock_preset\Form\Util\PresetConfUtil;

#[Route('/admin/structure/ock_preset/{interface}/preset/{preset_name}')]
#[RouteIsAdmin]
#[RouteTitleMethod([self::class, 'title'])]
#[RouteRequirePermission('administer ock_preset')]
#[RouteParameters(['interface' => ParamConverter_Iface::TYPE])]
class Controller_Preset extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(
    FormBuilderInterface $formBuilder,
    ConfigFactoryInterface $configFactory,
  ) {
    // Initialize base class properties.
    $this->configFactory = $configFactory;
    $this->formBuilder = $formBuilder;
  }

  /**
   * Creates a builder object to create links and urls.
   *
   * @param string $interface
   *   Interface to use in the url.
   * @param string $presetName
   *   Preset machine name to use in the url.
   * @param string $methodName
   *   Method name for a sub-page.
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   *   Builder object to create links and urls.
   */
  public static function route(string $interface, string $presetName, string $methodName = 'edit'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
        'preset_name' => $presetName,
      ],
      $methodName);
  }

  /**
   * Gets the page title.
   *
   * @param string $interface
   *   Interface parsed from url.
   * @param string $preset_name
   *   Preset machine name parsed from url.
   *
   * @return string|MarkupInterface
   *   Page title.
   */
  public function title(string $interface, string $preset_name): string|MarkupInterface {

    $config = $this->getConfig($interface, $preset_name);

    if ($config->isNew()) {
      return $this->t('Unknown preset.');
    }

    return $config->get('label');
  }

  /**
   * Shows a page with a preset edit form.
   *
   * @param string $interface
   *   Interface name parsed from url.
   * @param string $preset_name
   *   Preset machine name parsed from url.
   *
   * @return array
   *   Page render element including the form.
   */
  #[Route]
  #[RouteDefaultTaskLink('Edit')]
  public function edit(string $interface, string $preset_name): array {
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
        $config->get('conf'),
      );
    $page['form'] = $this->formBuilder->getForm(
      Form_Decorator::class,
      $formObject,
    );

    return $page;
  }

  /**
   * Shows a page with a preset delete confirm form.
   *
   * @param string $interface
   *   Interface name parsed from url.
   * @param string $preset_name
   *   Preset machine name parsed from url.
   *
   * @return array
   *   Page render element including the form.
   */
  #[Route('/delete')]
  #[RouteTaskLink('Delete')]
  public function delete(string $interface, string $preset_name): array {
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
      $config->get('conf'),
    );
    $page['form'] = $this->formBuilder->getForm(
      Form_Decorator::class,
      $formObject,
    );

    return $page;
  }

  /**
   * Gets the configuration for a specific preset.
   *
   * @param string $interface
   *   Interface name.
   * @param string $preset
   *   Preset machine name.
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   *   Configuration.
   */
  private function getConfig(string $interface, string $preset): ImmutableConfig {
    $key = PresetConfUtil::presetConfKey($interface, $preset);
    return $this->configFactory->get($key);
  }

}
