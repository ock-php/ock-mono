<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Controller;

use Donquixote\Cf\Evaluator\Evaluator;
use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\controller_annotations\Configuration\Cache;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\controller_annotations\RouteModifier\RouteIsAdmin;
use Drupal\controller_annotations\RouteModifier\RouteRequirePermission;
use Drupal\controller_annotations\RouteModifier\RouteTitle;
use Drupal\Core\Controller\ControllerBase;
use Drupal\faktoria\Form\Form_IfaceDemo;
use Drupal\faktoria\Hub\CfrPluginHub;
use Drupal\faktoria\Util\UiDumpUtil;
use Drupal\renderkit8\BuildProvider\BuildProviderInterface;
use Drupal\routelink\RouteModifier\RouteMenuLink;

/**
 * @Route("/admin/reports/renderkit")
 * @Cache(expires="tomorrow")
 * @RouteIsAdmin
 * @RouteRequirePermission("view renderkit report pages")
 */
class Controller_Report extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @Route
   * @RouteTitle("Renderkit")
   * @RouteMenuLink
   *
   * @return array
   */
  public function index(): array {
    return [
      '#markup' => __METHOD__,
    ];
  }

  /**
   * @Route("/build-provider-demo")
   * @RouteTitle("Build provider demo")
   * @RouteMenuLink
   *
   * @return array
   */
  public function demo(): array {

    $settings = $_GET['plugin'] ?? [];

    $out = [];

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    $out['form'] = $this->formBuilder()->getForm(
      Form_IfaceDemo::class,
      BuildProviderInterface::class);

    if (!empty($_GET['noshow']) || !empty($_POST)) {
      return $out;
    }

    $container = CfrPluginHub::getContainer();
    $sta = $container->schemaToAnything;

    if (!$settings) {
      return $out;
    }

    $schema = new CfSchema_IfaceWithContext(BuildProviderInterface::class);

    $evaluator = Evaluator::fromSchema($schema, $sta);

    if (null === $evaluator) {

      $out['problem'] = [
        '#type' => 'fieldset',
        '#title' => t('Problem'),
        'data' => [
          '#markup' => t('Failed to create evaluator'),
        ],
      ];

      return $out;
    }

    try {
      $object = $evaluator->confGetValue($settings);
    }
    catch (\Exception $e) {
      if ($e instanceof EvaluatorException) {
        drupal_set_message(t('The configuration could not be evaluated.'), 'warning');
      }
      else {
        drupal_set_message(
          t(
            'The @evaluator_class::confGetValue() method threw a @exception_class exception.',
            [
              '@evaluator_class' => \get_class($evaluator),
              '@exception_class' => \get_class($e),
            ]),
          'warning');
      }
      $out['exception'] = [
          '#type' => 'fieldset',
          '#title' => t('Exception'),
          '#description' => '<p>' . t('Cfrplugin was unable to generate a behavior object for the given configuration.') . '</p>',
          '#id' => '_',
        ]
        + UiDumpUtil::displayException($e);

      return $out;
    }

    if (!$object instanceof BuildProviderInterface) {

      drupal_set_message(
        t(
          'The @evaluator_class::confGetValue() method had an unexpected return value.',
          [
            '@evaluator_class' => \get_class($evaluator),
          ]),
        'warning');
      $out['object'] = UiDumpUtil::dumpDataInFieldset($object, t('Unexpected value or object'));

      return $out;
    }

    $out['build'] = [
      '#type' => 'fieldset',
      '#title' => t('Output'),
      'build' => $object->build(),
    ];

    return $out;
  }

}
