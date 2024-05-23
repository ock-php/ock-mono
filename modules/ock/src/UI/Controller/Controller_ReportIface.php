<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;
use Donquixote\Ock\Summarizer\Summarizer;
use Donquixote\Ock\Translator\TranslatorInterface;
use Donquixote\Ock\Util\HtmlUtil;
use Donquixote\DID\Util\PhpUtil;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Serialization\Yaml;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteDefaultTaskLink;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteParameters;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTaskLink;
use Drupal\ock\Attribute\Routing\RouteTitleMethod;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\UI\Form\Form_IfaceDemo;
use Drupal\ock\UI\ParamConverter\ParamConverter_Iface;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiCodeUtil;
use Drupal\ock\Util\UiUtil;

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
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
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
   * @throws \Donquixote\Ock\Exception\PluginListException
   */
  #[Route]
  #[RouteDefaultTaskLink('List of plugins')]
  public function listOfPlugins(string $interface): array {
    /**
     * @var array[] $rows
     *   Format: $[] = $row
     * @var array[][] $rows_grouped
     *   Format: $[$groupLabel][] = $row
     */
    $rows = [];
    foreach ($this->pluginMap->typeGetPlugins($interface) as $key => $plugin) {

      $label = $plugin->getLabel()->convert($this->translator);

      $row = [
        Controller_ReportPlugin::route($interface, $key)
          ->link($label),

        Markup::create('<code>' . HtmlUtil::sanitize($key) . '</code>'),

        self::route($interface)->subpage('demo')->link(
          $this->t('Demo'),
          [
            'query' => [
              'plugin[id]' => $key,
              'noshow' => TRUE,
            ],
          ]),
      ];

      $rows[] = $row;
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
      $php = $generator->confGetPhp($settings);
    }
    catch (GeneratorException) {
      // @todo Better exception reporting.
      $php = '?';
    }
    $php = PhpUtil::autoIndent($php, '  ', '    ');
    $attribute_class = OckPluginInstance::class;
    $php = <<<EOT
class Example {

  #[\\$attribute_class('examplePlugin', 'Example plugin')]
  public static function create(): \\$interface {
    return $php;
  }
}
EOT;


    $aliases = PhpUtil::aliasify($php);
    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    if ('' !== $aliases_php) {
      $aliases_php = "\n" . $aliases_php;
    }

    $php = <<<EOT
<?php

declare(strict_types = 1);

namespace Drupal\EXAMPLE\SubNamespace;
$aliases_php
$php
EOT;

    $out['codegen']['help']['#markup'] = $this->t(
    // @todo Is it a good idea to send full HTML to t()?
      <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
EOT
    );

    $out['codegen']['code']['#children'] = UiCodeUtil::highlightPhp($php);

    return $out;
  }
}
