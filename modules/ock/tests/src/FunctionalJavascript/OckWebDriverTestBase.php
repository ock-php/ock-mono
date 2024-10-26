<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Enhanced base class for web driver tests.
 */
class OckWebDriverTestBase extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

}
