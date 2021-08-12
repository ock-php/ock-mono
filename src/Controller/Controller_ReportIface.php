<?php
declare(strict_types=1);

namespace Drupal\cu\Controller;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Generator\Generator;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;
use Donquixote\OCUI\Summarizer\Summarizer;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\HtmlUtil;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteParameters;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitleMethod;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\cu\Form\Form_IfaceDemo;
use Drupal\cu\RouteHelper\ClassRouteHelper;
use Drupal\cu\RouteHelper\ClassRouteHelperInterface;
use Drupal\cu\Util\StringUtil;
use Drupal\cu\Util\UiCodeUtil;
use Drupal\cu\Util\UiUtil;
use Drupal\routelink\RouteModifier\RouteDefaultTaskLink;
use Drupal\routelink\RouteModifier\RouteTaskLink;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Route("/admin/reports/cu/{interface}")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("view cu report")
 * @RouteParameters(interface = "cu:interface")
 *
 * @see \Drupal\cu\ParamConverter\ParamConverter_Iface
 */
class Controller_ReportIface extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  private PluginMapInterface $pluginMap;

  private FormulaToAnythingInterface $formulaToAnything;

  private TranslatorInterface $translator;

  /**
   * @param string $interface
   * @param string $methodName
   *
   * @return \Drupal\cu\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(string $interface, string $methodName = 'listOfPlugins'): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
      ],
      $methodName);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    /** @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface $plugin_map */
    $plugin_map = $container->get(PluginMapInterface::class);
    /** @var \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formula_to_anything */
    $formula_to_anything = $container->get(FormulaToAnythingInterface::class);
    /** @var \Donquixote\OCUI\Translator\TranslatorInterface $translator */
    $translator = $container->get(TranslatorInterface::class);
    return new self($plugin_map, $formula_to_anything, $translator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $plugin_map
   *   Plugin map.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formula_to_anything
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   */
  public function __construct(PluginMapInterface $plugin_map, FormulaToAnythingInterface $formula_to_anything, TranslatorInterface $translator) {
    $this->pluginMap = $plugin_map;
    $this->formulaToAnything = $formula_to_anything;
    $this->translator = $translator;
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
   * @Route
   * @RouteDefaultTaskLink("List of plugins")
   *
   * @param string $interface
   *
   * @return array
   */
  public function listOfPlugins(string $interface): array {

    /**
     * @var array[] $rows
     *   Format: $[] = $row
     * @var array[][] $rows_grouped
     *   Format: $[$groupLabel][] = $row
     */
    $rows = [];
    $rows_grouped = [];
    foreach ($this->pluginMap->typeGetPlugins($interface) as $key => $plugin) {

      $label = $plugin->getLabelOr($key)->convert($this->translator);

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

    foreach ($rows_grouped as $groupLabel => $rowsInGroup) {
      $rows[] = [
        [
          'colspan' => 5,
          'data' => ['#markup' => '<h3>' . HtmlUtil::sanitize($groupLabel) . '</h3>'],
        ],
      ];
      foreach ($rowsInGroup as $row) {
        $rows[] = $row;
      }
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

  /**
   * @Route("/code")
   * @RouteTaskLink("Code")
   *
   * @param string $interface
   *
   * @return array
   */
  public function code($interface): array {

    $html = UiCodeUtil::classGetCodeAsHtml($interface);

    return [
      '#children' => $html,
    ];
  }

  /**
   * @Route("/demo")
   * @RouteTaskLink("Demo")
   *
   * @param string $interface
   *
   * @return array
   */
  public function demo($interface): array {

    $settings = $_GET[Form_IfaceDemo::KEY] ?? [];

    $out = [];

    $out['form'] = $this->formBuilder()->getForm(
      Form_IfaceDemo::class,
      $interface);

    if (!empty($_GET['noshow']) || !empty($_POST)) {
      return $out;
    }

    $sta = $this->formulaToAnything;

    if (!$settings) {
      return $out;
    }

    $formula = Formula::iface($interface);

    try {
      $summary_obj = Summarizer::fromFormula($formula, $sta)
        ->confGetSummary($settings);
      $summary = $summary_obj
        ? $summary_obj->convert($this->translator)
        : '?';
    }
    catch (FormulaToAnythingException $e) {
      $summary = '?';
    }

    $out['summary'] = [
      '#type' => 'fieldset',
      '#title' => t('Plugin summary'),
      'summary' => [
        '#markup' => $summary,
      ],
    ];

    $out['conf'] = [
      '#type' => 'fieldset',
      '#title' => t('Configuration data'),
      'data' => [
        '#children' => UiCodeUtil::exportHighlightWrap($settings),
      ],
    ];

    try {
      $generator = Generator::fromFormula($formula, $sta);
    }
    catch (FormulaToAnythingException $e) {
      $out['problem'] = [
        '#type' => 'fieldset',
        '#title' => t('Problem'),
        'data' => [
          '#markup' => t('Failed to create evaluator'),
        ],
      ];
      return $out;
    }

    $out['codegen'] = [
      '#type' => 'fieldset',
      '#title' => t('Generated PHP code'),
    ];

    $php = $generator->confGetPhp($settings);
    $php = CodegenUtil::autoIndent($php, '  ', '    ');
    $aliases = CodegenUtil::aliasify($php);
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
$aliases_php
class C {

  /**
   * @CfrPlugin("myPlugin", "My plugin")
   *
   * @return \\$interface
   */
  public static function create() {

    return $php;
  }
}
EOT;

    $out['codegen']['help']['#markup'] = t(
    // @todo Is it a good idea to send full HTML to t()?
      <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
<p>If you do so, don't forget to:</p>
<ul>
  <li>Implement <code>hook_cu_info()</code> similar to how other modules do it.</li>
  <li>Set up a PSR-4 namespace directory structure for your class files.</li>
  <li>Replace "myPlugin", "My plugin" and "class C" with more suitable names, and put the class into a namespace.</li>
  <li>Leave the <code>@return</code> tag in place, because it tells cudiscovery about the plugin type.</li>
  <li>Fix all <code>@todo</code> items. These occur if code generation was incomplete.</li>
</ul>
EOT
    );

    $out['codegen']['code']['#children'] = UiCodeUtil::highlightPhp($php);

    return $out;
  }
}
