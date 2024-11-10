<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteActionLink;
use Drupal\controller_attributes\Attribute\RouteDefaultTaskLink;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTaskLink;
use Drupal\controller_attributes\Attribute\RouteTitleMethod;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\ock\TextToDrupal\TextToDrupalInterface;
use Drupal\ock\UI\ParamConverter\ParamConverter_Iface;
use Drupal\ock\UI\RouteHelper\ClassRouteHelper;
use Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface;
use Drupal\ock\Util\StringUtil;
use Drupal\ock\Util\UiUtil;
use Drupal\ock_preset\Crud\PresetRepository;
use Drupal\ock_preset\Form\Form_Decorator;
use Drupal\ock_preset\Form\Form_PresetEdit;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Summarizer\Summarizer;

#[Route('/admin/structure/ock_preset/{interface}')]
#[RouteIsAdmin]
#[RouteTitleMethod([self::class, 'title'])]
#[RouteRequirePermission('administer ock_preset')]
#[RouteParameters(['interface' => ParamConverter_Iface::TYPE])]
class Controller_IfacePresets extends ControllerBase implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  /**
   * Constructor.
   *
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *   Universal adapter.
   * @param \Drupal\ock\TextToDrupal\TextToDrupalInterface $textToDrupal
   *   Converts ock text to Drupal markup.
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   *   Form builder.
   */
  public function __construct(
    private readonly UniversalAdapterInterface $adapter,
    private readonly TextToDrupalInterface $textToDrupal,
    FormBuilderInterface $formBuilder,
  ) {
    // The base class has this property, but it does not initialize it.
    $this->formBuilder = $formBuilder;
  }

  /**
   * Gets a builder object to create links and urls.
   *
   * @param string $interface
   *   Interface to use in the url.
   * @param string $methodName
   *   One of the method names in this class.
   *
   * @return \Drupal\ock\UI\RouteHelper\ClassRouteHelperInterface
   *   Builder object for urls and links.
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
   * Shows a list of presets for a given interface.
   *
   * @param string $interface
   *   Interface name.
   *
   * @return array
   *   Page content render element.
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   *   The summarizer cannot be created.
   * @throws \Ock\Ock\Exception\SummarizerException
   *   One of the summaries cannot be created.
   */
  #[Route]
  #[RouteDefaultTaskLink('List')]
  public function index(string $interface): array {
    $summarizer = Summarizer::fromIface($interface, $this->adapter);
    $configs = PresetRepository::create()->loadForInterface($interface);

    $rows = [];
    foreach ($configs as $machine_name => $config) {

      $conf = $config->get('conf');
      $summary = $summarizer->confGetSummary($conf);

      $presetRouteHelper = Controller_Preset::route($interface, $machine_name);

      $row = [];
      $row[] = $config->get('label');
      $row[] = $presetRouteHelper
        ->link($this->t('edit'));
      $row[] = $presetRouteHelper
        ->subpage('delete')
        ->link($this->t('delete'));
      $row[] = $this->textToDrupal->convert($summary);

      $rows[] = $row;
    }

    $page = [];

    $interfaceLabel = StringUtil::interfaceGenerateLabel($interface);

    $page['#title'] = $this->t(
      'Manage %type plugin presets',
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
    $page['form'] = $this->formBuilder->getForm(
      Form_Decorator::class,
      $formObject,
    );

    return $page;
  }

}
