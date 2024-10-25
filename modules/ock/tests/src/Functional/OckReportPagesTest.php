<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\Functional;

use Drupal\Tests\BrowserTestBase;

class OckReportPagesTest extends BrowserTestBase {

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
   * Tests the ock reports overview page.
   */
  public function testReportsOverviewPage(): void {
    $this->drupalGet('/admin/reports/ock');
    $this->assertLink('AnimalInterface', '/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface/code');
    $this->assertLink('Plant', '/admin/reports/ock/Drupal.ock_example.Plant.PlantInterface/demo');
  }

  /**
   * Tests the plugin list page for an interface.
   */
  public function testReportInterfaceListPage(): void {
    $this->drupalGet('/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface');
    $this->assertLink('Elephant', '/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface/plugin/elephant');
    $this->assertLink('Giraffe', '/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface/plugin/giraffe');
  }

  /**
   * Tests the code view page for an interface.
   */
  public function testReportInterfaceCodePage(): void {
    $this->drupalGet('/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface/code');
    $this->assertSession()->pageTextContains('interface AnimalInterface');
  }

  /**
   * Tests the demo form page for an interface.
   */
  public function testReportInterfaceDemoPage(): void {
    $this->drupalGet('/admin/reports/ock/Drupal.ock_example.Animal.AnimalInterface/demo');
    $this->assertSession()->selectExists('Plugin')->selectOption('Elephant');
    $this->assertSession()->buttonExists('Show')->click();
    $this->assertSession()->pageTextContains('new Animal_Elephant()');
  }

  /**
   * Asserts that a link with given text and url is found on the page.
   *
   * @param string $expected_text
   *   Expected link text.
   * @param string $expected_href
   *   Expected link url.
   */
  protected function assertLink(string $expected_text, string $expected_href): void {
    $link = $this->getSession()->getPage()->findLink($expected_text);
    if ($link === NULL) {
      $this->fail("Link '$expected_text' not found.");
    }
    $actual_href = $link->getAttribute('href');
    $this->assertSame($expected_href, $actual_href, "Link url for '$expected_text'.");
  }

}
