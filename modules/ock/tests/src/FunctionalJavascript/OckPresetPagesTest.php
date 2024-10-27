<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

use Drupal\ock_example\Animal\Animal_Elephant;
use Drupal\ock_example\Plant\Plant_EnchantedCreature;
use Drupal\ock_example\Plant\PlantInterface;
use Drupal\ock_preset\Crud\PresetRepository;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Evaluator\Evaluator;
use Ock\Ock\Generator\Generator;
use Ock\Ock\Summarizer\Summarizer;
use Ock\Ock\Translator\Translator;
use WebDriver\Service\CurlService;
use WebDriver\ServiceFactory;

class OckPresetPagesTest extends OckWebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'ock',
    'ock_example',
    'ock_preset',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    ServiceFactory::getInstance()->setServiceClass(
      'service.curl',
      get_class(new class () extends CurlService {
        public function __construct($defaultOptions = []) {
          /** @noinspection PhpComposerExtensionStubsInspection */
          $defaultOptions[CURLOPT_TIMEOUT] = 1;
          parent::__construct($defaultOptions);
        }
      }),
    );

    parent::setUp();

    $this->drupalPlaceBlock('local_tasks_block');
    $this->drupalPlaceBlock('local_actions_block');

    $this->drupalLogin($this->createUser([
      'administer ock_preset',
    ]));
  }

  /**
   * Tests the UI to manage presets.
   *
   * @covers \Drupal\ock_preset\Controller\Controller_AllPresetsOverview::index()
   * @covers \Drupal\ock_preset\Controller\Controller_IfacePresets::index()
   * @covers \Drupal\ock_preset\Controller\Controller_IfacePresets::add()
   * @covers \Drupal\ock_preset\Controller\Controller_Preset::edit()
   * @covers \Drupal\ock_preset\Controller\Controller_Preset::delete()
   */
  public function testManagePresetsUI(): void {
    // On the overview page.
    $this->drupalGet('/admin/structure/ock_preset');
    $this->assertPageTitle('ock_preset presets');
    $this->linkExists('Animal', '/admin/structure/ock_preset/Drupal.ock_example.Animal.AnimalInterface')->click();

    // On the list page.
    $this->assertPageTitle('Manage Animal plugin presets');
    $this->linkExists('Add preset')->click();

    // On the create preset page.
    $this->assertPageTitle('Create Animal plugin preset');
    $this->assertSession()->fieldExists('Administrative title')->setValue('Laura Longneck');
    $this->selectAndWait('Plugin', 'Giraffe');
    $this->assertSession()->buttonExists('Save preset')->click();

    // Back on the list page.
    $this->assertPageTitle('Manage Animal plugin presets');
    $table_row = $this->elementWithTextExists('tr', 'Laura Longneck');
    $this->linkExists('edit', parent: $table_row)->click();

    // On the preset edit page.
    $this->assertPageTitle('Laura Longneck');
    $this->assertStringEndsWith('/laura_longneck', $this->getUrl());
    $this->assertSame('giraffe', $this->assertSession()->selectExists('Plugin')->getValue());
    $title_field = $this->assertSession()->fieldExists('Administrative title');
    $this->assertSame('Laura Longneck', $title_field->getValue());
    $title_field->setValue('Laura 1 Longneck');
    $this->assertSession()->linkExists('Delete');
    $this->assertSession()->buttonExists('Save preset')->click();

    // Back on the list page.
    $this->assertPageTitle('Manage Animal plugin presets');
    $table_row = $this->elementWithTextExists('tr', 'Laura 1 Longneck');
    $this->linkExists('delete', parent: $table_row)->click();

    // On the preset delete page.
    $this->assertPageTitle('Laura 1 Longneck');
    $this->assertSession()->pageTextContains('This action cannot be undone.');
    $this->assertSession()->linkExists('Cancel');
    $this->assertSession()->buttonExists('Delete')->click();

    // Back on the list page.
    $this->assertSession()->pageTextContains('The preset Laura 1 Longneck has been deleted.');
    $this->getSession()->reload();

    // Back on the list page, refreshed.
    $this->assertSession()->pageTextNotContains('Laura');
  }

  /**
   * Tests using one preset as part of another preset.
   */
  public function testPresetAsPlugin(): void {
    /** @var \Drupal\ock_preset\Crud\PresetRepository $repo */
    $repo = \Drupal::service(PresetRepository::class);
    /** @var \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter */
    $adapter = \Drupal::service(UniversalAdapterInterface::class);

    // Create an animal preset.
    $this->drupalGet('/admin/structure/ock_preset/Drupal.ock_example.Animal.AnimalInterface/add');
    $this->assertSession()->fieldExists('Administrative title')->setValue('Pink elephant');
    $this->selectAndWait('Plugin', 'Elephant');
    $this->assertSession()->buttonExists('Save preset')->click();

    // Make sure the new preset option is available in the select.
    $this->drupalGet('/admin/structure/ock_preset/Drupal.ock_example.Animal.AnimalInterface/add');
    $this->selectAndWait('Plugin', 'Preset: Pink elephant');

    // Use the new preset within another preset.
    $this->drupalGet('/admin/structure/ock_preset/Drupal.ock_example.Plant.PlantInterface/add');
    $this->assertSession()->fieldExists('Administrative title')->setValue('Enchanted pink elephant');
    $this->selectAndWait('Plugin', 'Enchanted creature…');
    $this->selectAndWait('Animal', 'Preset: Pink elephant');
    $this->assertSession()->buttonExists('Save preset')->click();

    $config = $repo->load(PlantInterface::class, 'enchanted_pink_elephant');
    $this->assertFalse($config->isNew());
    $this->assertSame(
      [
        'label' => 'Enchanted pink elephant',
        'conf' => [
          'id' => 'enchantedCreature',
          'options' => [
            'animal' => [
              'id' => 'pink_elephant',
            ],
          ],
        ],
      ],
      $config->getRawData(),
    );

    foreach ([
      'preset id' => ['id' => 'enchanted_pink_elephant'],
      'stored config' => $config->get('conf'),
    ] as $name => $conf) {
      $php = Generator::fromIface(PlantInterface::class, $adapter)
        ->confGetPhp($conf);
      $this->assertSame(
        <<<PHP
new \Drupal\ock_example\Plant\Plant_EnchantedCreature(
new \Drupal\ock_example\Animal\Animal_Elephant(),
)
PHP,
        $php,
        "Generated php expression for $name",
      );

      $object = Evaluator::iface(PlantInterface::class, $adapter)
        ->confGetValue($conf);
      $this->assertInstanceOf(Plant_EnchantedCreature::class, $object);
      $this->assertInstanceOf(Animal_Elephant::class, $object->enchanted_animal);

      $summary = Summarizer::fromIface(PlantInterface::class, $adapter)
        ->confGetSummary($conf);
      $this->assertSame(
        match ($name) {
          'preset id' => 'Preset: Enchanted pink elephant',
          'stored config' => 'Enchanted creature: <ul><li>Animal: Preset: Pink elephant</li></ul>',
        },
        $summary->convert(Translator::passthru()),
        "Generated summary for $name",
      );
    }
  }

}
