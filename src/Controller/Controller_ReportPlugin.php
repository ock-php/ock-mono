<?php
declare(strict_types=1);

namespace Drupal\ock\Controller;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Plugin\NamedPlugin;
use Donquixote\Ock\Summarizer\Summarizer;
use Donquixote\Ock\Translator\TranslatorInterface;
use Donquixote\Ock\Util\HtmlUtil;
use Drupal\controller_annotations\Configuration\Cache;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteParameters;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\controller_annotations\Configuration\RouteTitleMethod;
use Drupal\controller_annotations\Controller\ControllerRouteNameInterface;
use Drupal\controller_annotations\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock\Form\Form_FormulaDemo;
use Drupal\ock\RouteHelper\ClassRouteHelper;
use Drupal\ock\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiCodeUtil;
use Drupal\ock\Util\UiDumpUtil;
use Drupal\ock\Util\UiFormulaUtil;
use Drupal\ock\Util\UiUtil;
use Drupal\routelink\RouteModifier\RouteDefaultTaskLink;
use Drupal\routelink\RouteModifier\RouteTaskLink;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Route("/admin/reports/ock/{interface}/plugin/{named_plugin}")
 * @Cache(expires="tomorrow")
 * @RouteIsAdmin
 * @RouteTitleMethod("title")
 * @RouteRequirePermission("view ock report")
 * @RouteParameters(
 *   interface = "ock:interface",
 *   named_plugin = "ock:plugin"
 * )
 */
class Controller_ReportPlugin extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $incarnator;

  /**
   * @var \Donquixote\Ock\Translator\TranslatorInterface
   */
  private TranslatorInterface $translator;

  /**
   * @param string $interface
   * @param string $id
   *
   * @return \Drupal\ock\RouteHelper\ClassRouteHelperInterface
   */
  public static function route($interface, $id): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
        'named_plugin' => $id,
      ],
      'plugin');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    /** @var IncarnatorInterface $formula_to_anything */
    $formula_to_anything = $container->get(IncarnatorInterface::class);
    /** @var \Donquixote\Ock\Translator\TranslatorInterface $translator */
    $translator = $container->get(TranslatorInterface::class);
    return new self($formula_to_anything, $translator);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $formula_to_anything
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(IncarnatorInterface $formula_to_anything, TranslatorInterface $translator) {
    $this->incarnator = $formula_to_anything;
    $this->translator = $translator;
  }

  /**
   * @param \Donquixote\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return string
   */
  public function title(NamedPlugin $named_plugin): string {
    $label = $named_plugin->getPlugin()->getLabel();
    if ($label === NULL) {
      return $named_plugin->getId();
    }
    return $label->convert($this->translator);
  }

  /**
   * @Route
   * @RouteDefaultTaskLink("Plugin")
   *
   * @param string $interface
   * @param \Donquixote\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  public function plugin(string $interface, NamedPlugin $named_plugin): array {

    $plugin = $named_plugin->getPlugin();
    $id = $named_plugin->getId();

    $rows = [];

    $rows[] = [
      $this->t('Interface'),

      Markup::create(
        StringUtil::interfaceGenerateLabel($interface)
        . '<br/>'
        . '<code>' . HtmlUtil::sanitize($interface) . '</code>'
        . '<br/>'
        . Controller_ReportIface::route($interface)
          ->link(t('plugins'))
          ->toString()
        . ' | '
        . Controller_ReportIface::route($interface)
          ->subpage('code')
          ->link(t('code'))
          ->toString()),
    ];

    $rows[] = [
      $this->t('Label'),
      Markup::create('<h3>'
        . $plugin->getLabel()->convert($this->translator)
        . '</h3>'),
    ];

    $rows[] = [
      $this->t('Plugin'),
      UiCodeUtil::exportHighlightWrap($plugin),
    ];

    try {
      $formula = $plugin->getFormula();
      $reflObject = new \ReflectionObject($formula);
      $rows[] = [
        $this->t('Formula class'),
        UiCodeUtil::highlightAndWrap(''
          . "namespace " . $reflObject->getNamespaceName() . ";\n"
          . "\n"
          . "class " . $reflObject->getShortName() . " .. {..}"),
      ];
    }
    catch (\Exception $e) {
      $rows[] = [
        $this->t('Problem'),
        Markup::create('<pre>'
          . HtmlUtil::sanitize($e->getMessage())
          . '</pre>'),
      ];
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
   * @param \Donquixote\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array|string
   *
   * @throws \ReflectionException
   */
  public function code(NamedPlugin $named_plugin) {

    $plugin = $named_plugin->getPlugin();

    if (NULL === $class = UiFormulaUtil::formulaGetClass($plugin->getFormula())) {
      return $this->t('Cannot find a class name from the formula.');
    }

    $html = UiCodeUtil::classGetCodeAsHtml($class);

    return ['#children' => $html];
  }

  /**
   * @Route("/devel")
   * @RouteTaskLink("Devel")
   *
   * @param string $interface
   * @param \Donquixote\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  public function devel(string $interface, NamedPlugin $named_plugin): array {

    $id = $named_plugin->getId();
    $plugin = $named_plugin->getPlugin();

    $rows = [];

    $rows[] = [
      $this->t('Interface'),
      Markup::create(
        StringUtil::interfaceGenerateLabel($interface)
        . '<br/>'
        . '<code>' . HtmlUtil::sanitize($interface) . '</code>'
        . '<br/>'
        . Controller_ReportIface::route($interface)
          ->link(t('plugins'))
          ->toString()
        . ' | '
        . Controller_ReportIface::route($interface)
          ->subpage('code')
          ->link(t('code'))
          ->toString()
        . ''),
    ];

    $rows[] = [
      $this->t('Label'),
      Markup::create('<h3>' . $plugin->getLabel()->convert($this->translator) . '</h3>'),
    ];

    $rows[] = [
      $this->t('Formula'),
      Markup::create(
        '<pre>' . var_export($plugin->getFormula(), TRUE) . '</pre>'),
    ];

    // Just check if anything blows up.
    $formula = $plugin->getFormula();
    $rows[] = [
      $this->t('Factory formula object'),
      Markup::create(
        UiDumpUtil::dumpValue($formula)),
    ];


    try {
      $snippet = UiFormulaUtil::formulaGetCodeSnippet($formula);
    }
    catch (\Exception $e) {
      $snippet = NULL;
      $rows = array_merge($rows, UiDumpUtil::exceptionGetTableRows($e));
    }

    if ($snippet !== NULL) {
      $rows[] = [
        $this->t('Code snippet'),
        Markup::create(
          UiCodeUtil::highlightPhp(''
            . '<?php'
            . "\n[..]"
            . "\n"
            . "\n"
            . $snippet)),
      ];
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

  /**
   * @Route("/demo")
   * @RouteTaskLink("Demo")
   *
   * @param string $interface
   * @param \Donquixote\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  public function demo($interface, NamedPlugin $named_plugin): array {

    $plugin = $named_plugin->getPlugin();

    $out = [];

    $sta = $this->incarnator;

    $formula = $plugin->getFormula();

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    FALSE && $out['form'] = $this->formBuilder()->getForm(
      Form_FormulaDemo::class,
      $formula);

    if (0
      || !empty($_GET['noshow'])
      || !empty($_POST)
      || !array_key_exists('conf', $_GET)
    ) {

      return $out;
    }

    $settings = $_GET['conf'];

    $summarizer = Summarizer::fromFormula($formula, $sta);

    $summary = NULL !== $summarizer
      ? $summarizer->confGetSummary($settings)
      : '?';

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
        '#children' => UiCodeUtil::exportHighlightWrap($settings),
      ],
    ];

    if (null === $generator = Generator::fromFormula($formula, $sta)) {

      $out['problem'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Problem'),
        'data' => [
          '#markup' => $this->t('Failed to create evaluator from formula.'),
        ],
      ];

      return $out;
    }

    $out['codegen'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Generated PHP code'),
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

    $out['codegen']['help']['#markup'] = $this->t(
    // @todo Is it a good idea to send full HTML to $this->t()?
      <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
<p>If you do so, don't forget to:</p>
<ul>
  <li>Implement <code>hook_ock_info()</code> similar to how other modules do it.</li>
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
