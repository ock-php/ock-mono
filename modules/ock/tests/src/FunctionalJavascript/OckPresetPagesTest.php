<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

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

}
