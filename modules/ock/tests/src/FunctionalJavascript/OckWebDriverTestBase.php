<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

use Behat\Mink\Element\NodeElement;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use WebDriver\Service\CurlService;
use WebDriver\ServiceFactory;

/**
 * Enhanced base class for web driver tests.
 */
class OckWebDriverTestBase extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    // Set a timeout for each web driver request, so that the test fails
    // instead of hanging if the web driver is unavailable. Some single
    // requests (session start, page navigation) block until the browser
    // is done, and can take several seconds in CI, so this must not be
    // too short. 1 second caused random failures.
    ServiceFactory::getInstance()->setServiceClass(
      'service.curl',
      get_class(new class () extends CurlService {
        public function __construct($defaultOptions = []) {
          $defaultOptions[CURLOPT_TIMEOUT] = 30;
          parent::__construct($defaultOptions);
        }
      }),
    );

    parent::setUp();
  }

  /**
   * Asserts the page title.
   *
   * @param string $title
   *   Expected page title.
   */
  protected function assertPageTitle(string $title): void {
    $title_element = $this->getSession()->getPage()->find('css', 'title');
    $actual_title = strip_tags($title_element?->getOuterHtml() ?? '');
    $this->assertSame($title . ' | Drupal', $actual_title);
  }

  /**
   * Asserts that a link with given text and url is found on the page.
   *
   * @param string $text
   *   Expected link text.
   * @param string|null $href
   *   Expected link url.
   * @param \Behat\Mink\Element\NodeElement|null $parent
   *   Optional parent element.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   Link element.
   */
  protected function linkExists(string $text, ?string $href = NULL, ?NodeElement $parent = NULL): NodeElement {
    $parent ??= $this->getSession()->getPage();
    $link = $parent->findLink($text);
    if ($link === NULL) {
      $this->fail("Link '$text' not found.");
    }
    $actual_href = $link->getAttribute('href');
    if ($href !== NULL) {
      $this->assertSame($href, $actual_href, "Link url for '$text'.");
    }
    return $link;
  }

  /**
   * Asserts and returns an element with tag and text.
   *
   * @param string $tag
   *   Tag name.
   * @param string $text
   *   Expected text that the element should contain.
   *
   * @return \Behat\Mink\Element\NodeElement
   *   The element.
   */
  protected function elementWithTextExists(string $tag, string $text): NodeElement {
    return $this->assertSession()->elementExists(
      'xpath',
      // @todo Escape the text somehow.
      "//{$tag}[contains(., '$text')]",
    );
  }

  /**
   * Selects a select option and waits until the select is not disabled.
   *
   * This is needed for select elements that trigger ajax requests.
   *
   * @param string $select
   *   One of id|name|label|value for the select field.
   * @param string $option
   *   Select option label.
   * @param int $max_seconds
   *   Maximum number of seconds to wait.
   */
  protected function selectAndWait(string $select, string $option, int $max_seconds = 10): void {
    $element = $this->assertSession()->selectExists($select);
    $element->selectOption($option);
    $this->assertSession()->assertWaitOnAjaxRequest($max_seconds * 1000);
    $this->assertFalse($element->hasAttribute('disabled'));
  }

}
