<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests interactive report pages under /en/admin/reports/ock/*.
 *
 * This only covers pages with interactive forms.
 * Other pages are covered in regular 'Functional' tests.
 *
 * @see \Drupal\ock\Element\FormElement_OckPlugin
 * @see \Drupal\ock\UI\Controller\Controller_ReportIface::demo()
 */
class OckReportPagesDemoFormTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ock',
    'ock_example',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();

    $this->drupalLogin($this->createUser(['view ock reports']));
  }

  /**
   * Tests the form on the demo page in reports section.
   */
  public function testInterfaceDemoPage(): void {
    $this->drupalGet('/admin/reports/ock/Drupal.ock_example.Plant.PlantInterface/demo');
    $this->assertSession()->selectExists('Plugin')
      ->selectOption('Enchanted creature…');
    $this->assertSession()->waitForId('edit-plugin-options-animal-id');
    $this->assertSession()->selectExists('Animal')
      ->selectOption('Elephant');
    $this->assertSession()->buttonExists('Show')->click();
    $this->assertSession()->pageTextContains('Animal: Elephant');
    $this->assertTrue(TRUE);
  }

}
