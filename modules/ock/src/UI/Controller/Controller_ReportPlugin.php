<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteDefaultTaskLink;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteParameters;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\Attribute\Routing\RouteTaskLink;
use Drupal\ock\Attribute\Routing\RouteTitleMethod;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Drupal\ock\UI\Form\Form_FormulaDemo;
use Drupal\ock\UI\ParamConverter\ParamConverter_Iface;
use Drupal\ock\UI\ParamConverter\ParamConverter_Plugin;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiCodeUtil;
use Drupal\ock\Util\UiDumpUtil;
use Drupal\ock\Util\UiFormulaUtil;
use Drupal\ock\Util\UiUtil;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\CodegenTools\CodeFormatter;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Exception\FormulaException;
use Ock\Ock\Generator\Generator;
use Ock\Ock\Plugin\NamedPlugin;
use Ock\Ock\Summarizer\Summarizer;
use Ock\Ock\Translator\TranslatorInterface;

#[Route('/admin/reports/ock/{interface}/plugin/{named_plugin}')]
#[RouteIsAdmin]
#[RouteTitleMethod([self::class, 'title'])]
#[RouteRequirePermission('view ock reports')]
#[RouteParameters([
  'interface' => ParamConverter_Iface::TYPE,
  'named_plugin' => ParamConverter_Plugin::TYPE,
])]
class Controller_ReportPlugin extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;
  use ContainerInjectionViaAttributesTrait;

  /**
   * @param string $interface
   * @param string $id
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   */
  public static function route(string $interface, string $id): ClassRouteHelperInterface {
    return ClassRouteHelper::fromClassName(
      self::class,
      [
        'interface' => UiUtil::interfaceGetSlug($interface),
        'named_plugin' => $id,
      ],
      'plugin');
  }

  /**
   * Constructor.
   *
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    #[GetService]
    private readonly UniversalAdapterInterface $adapter,
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {}

  /**
   * @param \Ock\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return string
   */
  public function title(NamedPlugin $named_plugin): string {
    return $named_plugin->getPlugin()->getLabel()
      ->convert($this->translator);
  }

  /**
   * @param string $interface
   * @param \Ock\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  #[Route]
  #[RouteDefaultTaskLink('Plugin')]
  public function plugin(string $interface, NamedPlugin $named_plugin): array {
    $plugin = $named_plugin->getPlugin();
    $rows = [];

    $rows[] = [
      $this->t('Interface'),
      new FormattableMarkup(
        <<<'EOT'
@interface_label<br>
<code>@interface</code><br>
@link | @code_link
EOT,
        [
          '@interface_label' => StringUtil::interfaceGenerateLabel($interface),
          '@interface' => $interface,
          '@link' => Controller_ReportIface::route($interface)
            ->link(t('plugins'))
            ->toString(),
          '@code_link' => Controller_ReportIface::route($interface)
            ->subpage('code')
            ->link(t('code'))
            ->toString(),
        ],
      ),
    ];

    $rows[] = [
      $this->t('Label'),
      new FormattableMarkup('<h3>@label</h3>', [
        '@label' => Markup::create($plugin->getLabel()->convert($this->translator)),
      ]),
    ];

    $rows[] = [
      $this->t('Plugin'),
      // @todo This output is not useful most of the time.
      UiCodeUtil::exportHighlightWrap($plugin),
    ];

    try {
      // @todo Is this code snippet really useful?
      $formula = $plugin->getFormula();
      $reflObject = new \ReflectionObject($formula);
      $php_file_contents = <<<PHP
<?php

declare(strict_types=1);

namespace {$reflObject->getNamespaceName()};

class {$reflObject->getShortName()} .. {..}
PHP;

      $rows[] = [
        $this->t('Formula class'),
        Markup::create(UiCodeUtil::highlightPhp($php_file_contents)),
      ];
    }
    // @todo When does this exception ever occur?
    catch (\Exception $e) {
      $rows[] = [
        $this->t('Problem'),
        new FormattableMarkup('<pre>@message</pre>', ['@message' => $e->getMessage()]),
      ];
    }

    return [
      '#theme' => 'table',
      '#rows' => $rows,
    ];
  }

  /**
   * @param \Ock\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array|\Drupal\Component\Render\MarkupInterface
   *
   * @throws \ReflectionException
   */
  #[Route('/code')]
  #[RouteTaskLink('Code')]
  public function code(NamedPlugin $named_plugin): MarkupInterface|array {

    $plugin = $named_plugin->getPlugin();

    if (NULL === $class = UiFormulaUtil::formulaGetClass($plugin->getFormula())) {
      return $this->t('Cannot find a class name from the formula.');
    }

    $html = UiCodeUtil::classGetCodeAsHtml($class);

    return ['#children' => $html];
  }

  /**
   * @param string $interface
   * @param \Ock\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  #[Route('/devel')]
  #[RouteTaskLink('Devel')]
  public function devel(string $interface, NamedPlugin $named_plugin): array {
    $plugin = $named_plugin->getPlugin();
    $rows = [];

    $rows[] = [
      $this->t('Interface'),
      new FormattableMarkup(
        <<<'EOT'
@interface_label<br>
<code>@interface</code><br>
@link | @code_link
EOT,
        [
          '@interface_label' => StringUtil::interfaceGenerateLabel($interface),
          '@interface' => $interface,
          '@link' => Controller_ReportIface::route($interface)
            ->link(t('plugins'))
            ->toString(),
          '@code_link' => Controller_ReportIface::route($interface)
            ->subpage('code')
            ->link(t('code'))
            ->toString(),
        ],
      ),
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
   * @param $interface
   * @param \Ock\Ock\Plugin\NamedPlugin $named_plugin
   *
   * @return array
   */
  #[Route('/demo')]
  #[RouteTaskLink('Demo')]
  public function demo($interface, NamedPlugin $named_plugin): array {

    $plugin = $named_plugin->getPlugin();

    $out = [];

    $formula = $plugin->getFormula();

    $out['form'] = $this->formBuilder()->getForm(
      Form_FormulaDemo::class,
      $formula,
    );

    if (0
      || !empty($_GET['noshow'])
      || !empty($_POST)
      || !array_key_exists('conf', $_GET)
    ) {

      return $out;
    }

    $settings = $_GET['conf'];

    try {
      $summary = Summarizer::fromFormula($formula, $this->adapter)
        ->confGetSummary($settings)
        ?->convert($this->translator);
      $out['summary'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Plugin summary'),
        'summary' => [
          '#markup' => $summary ?? '-',
        ],
      ];
    }
    catch (AdapterException|FormulaException) {
      $out['summary'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Problem'),
        'summary' => [
          '#markup' => $this->t('Failed to create summarizer.'),
        ],
      ];
    }

    $out['conf'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Configuration data'),
      'data' => [
        '#children' => UiCodeUtil::exportHighlightWrap($settings),
      ],
    ];

    if (null === $generator = Generator::fromFormula($formula, $this->adapter)) {

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

    $php_expression = $generator->confGetPhp($settings);

    $php_class_declaration = <<<EOT
class C {

  /**
   * @CfrPlugin("myPlugin", "My plugin")
   *
   * @return \\$interface
   */
  public static function create() {
    return $php_expression;
  }

}
EOT;
    $php_file_contents = CodeFormatter::create()->formatAsFile($php_class_declaration);

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

    $out['codegen']['code']['#children'] = UiCodeUtil::highlightPhp($php_file_contents);

    return $out;
  }

}
