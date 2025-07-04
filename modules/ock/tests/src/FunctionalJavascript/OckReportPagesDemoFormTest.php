<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

/**
 * Tests interactive report pages under /en/admin/reports/ock/*.
 *
 * This only covers pages with interactive forms.
 * Other pages are covered in regular 'Functional' tests.
 *
 * @see \Drupal\ock\Element\FormElement_OckPlugin
 * @see \Drupal\ock\Controller\Controller_ReportIface::demo()
 */
class OckReportPagesDemoFormTest extends OckWebDriverTestBase {

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
  public function setUp(): void {
    parent::setUp();

    $this->drupalLogin($this->createUser(['view ock reports']));
  }

  /**
   * Tests the form on the demo page in reports section.
   */
  public function testInterfaceDemoPage(): void {
    $this->drupalGet('/admin/reports/ock/Drupal.ock_example.Plant.PlantInterface/demo');
    $this->selectAndWait('Plugin', 'Enchanted creature…');
    $this->selectAndWait('Animal', 'Elephant');
    $this->assertSession()->buttonExists('Show')->click();
    $this->assertSession()->pageTextContains('Animal: Elephant');
  }

}
