<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

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
    // Set a timeout for web driver requests.
    // Otherwise, the test will hang if the web driver is unavailable.
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
  }

}
