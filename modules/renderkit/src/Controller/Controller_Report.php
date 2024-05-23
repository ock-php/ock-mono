<?php
declare(strict_types=1);

namespace Drupal\renderkit\Controller;

use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Evaluator\Evaluator;
use Ock\DID\Exception\EvaluatorException;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Plugin\Map\PluginMapInterface;
use Ock\Ock\Translator\TranslatorInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteMenuLink;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTitle;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\UI\Controller\ControllerRouteNameInterface;
use Drupal\ock\UI\Controller\ControllerRouteNameTrait;
use Drupal\ock\UI\Form\Form_IfaceDemo;
use Drupal\ock\Util\UiDumpUtil;
use Drupal\renderkit\BuildProvider\BuildProviderInterface;

/**
 * @Cache(expires="tomorrow")
 * @todo Define cache via attributes.
 */
#[Route('/admin/reports/renderkit')]
#[RouteIsAdmin]
#[RouteRequirePermission('view renderkit report pages')]
class Controller_Report extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;
  use ContainerInjectionViaAttributesTrait;

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    #[GetService]
    private readonly PluginMapInterface $pluginMap,
    #[GetService]
    private readonly UniversalAdapterInterface $adapter,
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {}

  #[Route]
  #[RouteTitle('Renderkit')]
  #[RouteMenuLink]
  public function index(): array {
    return [
      '#markup' => __METHOD__,
    ];
  }

  #[Route('/build-provider-demo')]
  #[RouteTitle('Build provider demo')]
  #[RouteMenuLink]
  public function demo(): array {

    $settings = $_GET[Form_IfaceDemo::KEY] ?? [];

    $out = [];

    $out['form'] = $this->formBuilder()->getForm(
      Form_IfaceDemo::class,
      BuildProviderInterface::class,
    );

    if (!empty($_GET['noshow']) || !empty($_POST)) {
      return $out;
    }

    $sta = $this->adapter;

    if (!$settings) {
      return $out;
    }

    $formula = Formula::iface(BuildProviderInterface::class);

    try {
      $evaluator = Evaluator::fromFormula($formula, $sta);
    }
    catch (AdapterException) {
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
        \Drupal::messenger()->addMessage(t('The configuration could not be evaluated.'), 'warning');
      }
      else {
        \Drupal::messenger()->addMessage(
          t(
            'The @evaluator_class::confGetValue() method threw a @exception_class exception.',
            [
              '@evaluator_class' => \get_class($evaluator),
              '@exception_class' => \get_class($e),
            ]),
          'warning');
      }
      $out['exception'] = [
        /* @see \Drupal\Core\Render\Element\Fieldset */
        '#type' => 'fieldset',
        '#title' => t('Exception'),
        '#description' => '<p>' . t('Renderkit was unable to generate a behavior object for the given configuration.') . '</p>',
        '#description_display' => 'before',
        '#id' => '_',
      ];
      $out['exception'] += UiDumpUtil::displayException($e);

      return $out;
    }

    if (!$object instanceof BuildProviderInterface) {

      \Drupal::messenger()->addMessage(
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
