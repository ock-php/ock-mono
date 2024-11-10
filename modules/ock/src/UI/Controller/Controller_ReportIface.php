<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteDefaultTaskLink;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTaskLink;
use Drupal\controller_attributes\Attribute\RouteTitleMethod;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Serialization\Yaml;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\UI\Form\Form_IfaceDemo;
use Drupal\ock\UI\ParamConverter\ParamConverter_Iface;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiCodeUtil;
use Drupal\ock\Util\UiUtil;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\CodegenTools\CodeFormatter;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Exception\FormulaException;
use Ock\Ock\Exception\GeneratorException;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Generator\Generator;
use Ock\Ock\Plugin\Map\PluginMapInterface;
use Ock\Ock\Summarizer\Summarizer;
use Ock\Ock\Translator\TranslatorInterface;

/**
 * @see \Drupal\ock\UI\ParamConverter\ParamConverter_Iface
 */
#[Route('/admin/reports/ock/{interface}')]
#[RouteIsAdmin]
#[RouteTitleMethod([self::class, 'title'])]
#[RouteRequirePermission('view ock reports')]
#[RouteParameters(['interface' => ParamConverter_Iface::TYPE])]
class Controller_ReportIface extends ControllerBase implements ControllerRouteNameInterface {

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

  /**
   * @param string $interface
   * @param string $methodName
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(string $interface, string $methodName = 'listOfPlugins'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
      ],
      $methodName,
    );
  }

  /**
   * Title callback for the route below.
   *
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
   *
   * @throws \Ock\Ock\Exception\PluginListException
   */
  #[Route]
  #[RouteDefaultTaskLink('List of plugins')]
  public function listOfPlugins(string $interface): array {
    $rows = [];
    foreach ($this->pluginMap->typeGetPlugins($interface) as $key => $plugin) {
      $label = $plugin->getLabel()->convert($this->translator);
      $rows[] = [
        Controller_ReportPlugin::route($interface, $key)
          ->link($label),
        new FormattableMarkup('<code>@key</code>', ['@key' => $key]),
        self::route($interface)->subpage('demo')->link(
          $this->t('Demo'),
          [
            'query' => [
              'plugin[id]' => $key,
              'noshow' => TRUE,
            ],
          ],
        ),
      ];
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

  /**
   * @param string $interface
   *
   * @return array
   */
  #[Route('/code')]
  #[RouteTaskLink('Code')]
  public function code(string $interface): array {

    $html = UiCodeUtil::classGetCodeAsHtml($interface);

    return [
      '#children' => $html,
    ];
  }

  /**
   * @param string $interface
   *
   * @return array
   */
  #[Route('/demo')]
  #[RouteTaskLink('Demo')]
  public function demo(string $interface): array {

    $settings = $_GET[Form_IfaceDemo::KEY] ?? [];

    $out = [];

    $out['form'] = $this->formBuilder()->getForm(
      Form_IfaceDemo::class,
      $interface);

    if (!empty($_GET['noshow']) || !empty($_POST)) {
      return $out;
    }

    if (!$settings) {
      return $out;
    }

    $formula = Formula::iface($interface);

    try {
      $summary = Summarizer::fromFormula($formula, $this->adapter)
        ->confGetSummary($settings)
        ?->convert($this->translator)
        ?? '?';
    }
    catch (AdapterException) {
      // @todo Better exception reporting.
      $summary = '?';
    }
    catch (FormulaException) {
      // @todo Better exception reporting.
      $summary = '??';
    }

    $out['summary'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Plugin summary'),
      'summary' => [
        '#markup' => $summary,
      ],
    ];

    $out['conf'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Configuration data'),
      'data' => [
        '#markup' => UiCodeUtil::exportHighlightWrap($settings),
      ],
    ];

    $out['yaml'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Configuration data, YAML'),
      'data' => [
        # '#markup' => UiCodeUtil::exportHighlightWrap($settings),
        '#markup' => '<pre>'
          . Yaml::encode($settings)
          . '</pre>',
      ],
    ];

    try {
      $generator = Generator::fromFormula($formula, $this->adapter);
    }
    catch (AdapterException) {
      $out['problem'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Problem'),
        'data' => [
          '#markup' => $this->t('Failed to create a generator for the given formula'),
        ],
      ];
      return $out;
    }

    $out['codegen'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Generated PHP code'),
    ];

    try {
      $php_expression = $generator->confGetPhp($settings);
    }
    catch (GeneratorException) {
      // @todo Better exception reporting.
      $php_expression = '?';
    }
    $attribute_class = OckPluginInstance::class;
    $php_class_declaration = <<<EOT
class Example {

  #[\\$attribute_class('examplePlugin', 'Example plugin')]
  public static function create(): \\$interface {
    return $php_expression;
  }
}
EOT;

    $php_file_contents = CodeFormatter::create()
      ->formatAsFile($php_class_declaration, 'Drupal\EXAMPLE\SubNamespace');

    $out['codegen']['help']['#markup'] = $this->t(
    // @todo Is it a good idea to send full HTML to t()?
      <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
EOT
    );

    $out['codegen']['code']['#children'] = UiCodeUtil::highlightPhp($php_file_contents);

    return $out;
  }

}
