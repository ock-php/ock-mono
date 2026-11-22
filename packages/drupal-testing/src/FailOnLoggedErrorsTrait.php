<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

/**
 * Fails a functional test if errors were logged in the site under test.
 *
 * In a test, the PHP error log of the site under test is the error.log file
 * in the test site directory. Each request to the site under test writes PHP
 * errors there. Errors from the Drupal log are copied there by a logger.
 *
 * This is especially needed in WebDriver tests: There the browser sends the
 * requests to the site, so the test does not see server-side errors, unlike
 * in BrowserTestBase tests.
 *
 * The logger is registered in the services.yml of the test site. This way no
 * module needs to be installed, and the logger is active from the start of
 * the site install.
 *
 * @phpstan-require-extends \Drupal\Tests\BrowserTestBase
 *
 * @see \Drupal\Core\DrupalKernel::setupDrupalTestInChildSite()
 * @see \Drupal\Core\Test\FunctionalTestSetupTrait::prepareEnvironment()
 * @see \Ock\DrupalTesting\TestSiteServicesTrait
 */
trait FailOnLoggedErrorsTrait {

  use TestSiteServicesTrait;

  /**
   * Contents of the error log of the site under test, if not empty.
   *
   * This is set during tear down.
   *
   * @see self::cleanupEnvironment()
   */
  private ?string $loggedErrors = null;

  /**
   * {@inheritdoc}
   *
   * Registers a logger that copies errors from the Drupal log to error.log.
   */
  protected function prepareSettings(): void {
    parent::prepareSettings();
    $this->registerTestSiteService(FailOnLoggedErrorsLogger::class, [
      'autowire' => true,
      'tags' => [['name' => 'logger']],
    ]);
  }

  /**
   * {@inheritdoc}
   *
   * Fails the test if the error log of the site under test is not empty.
   *
   * This runs in tearDown() instead of a #[PostCondition] method, so that it
   * also runs when the test has already failed. PHPUnit then reports this
   * failure in addition to the main failure. This only works with
   * $this->fail(), other exceptions from tearDown() are dropped if the test
   * has already failed.
   *
   * If parent::tearDown() throws an exception, and the error log is not
   * empty, the failure reports both.
   *
   * @see \PHPUnit\Framework\TestCase::runBare()
   */
  protected function tearDown(): void {
    try {
      parent::tearDown();
    }
    catch (\Throwable $e) {
      // The exception may have happened before cleanupEnvironment().
      $this->loggedErrors ??= $this->readErrorLog();
      if ($this->loggedErrors === null) {
        throw $e;
      }
      $this->fail(
        "The PHP error log of the site under test is not empty:\n\n" . $this->loggedErrors
        . "\n\nAlso, an exception was thrown in tearDown():\n\n" . $e,
      );
    }
    if ($this->loggedErrors !== null) {
      $this->fail("The PHP error log of the site under test is not empty:\n\n" . $this->loggedErrors);
    }
  }

  /**
   * {@inheritdoc}
   *
   * Reads the error log before the site directory is deleted.
   *
   * This is called from parent::tearDown(), after pending ajax requests are
   * finished and the browser sessions are stopped. So no more requests to the
   * site under test can add to the error log after this point.
   *
   * @see \Drupal\FunctionalJavascriptTests\WebDriverTestBase::tearDown()
   * @see \Drupal\Tests\BrowserTestBase::tearDown()
   */
  protected function cleanupEnvironment(): void {
    $this->loggedErrors = $this->readErrorLog();
    parent::cleanupEnvironment();
  }

  /**
   * Reads the error log of the site under test.
   *
   * @return string|null
   *   Contents of the error log, or null if it is empty or does not exist.
   */
  protected function readErrorLog(): ?string {
    // The property is initialized as NULL.
    if ($this->siteDirectory === NULL) {
      // The setup did not get far enough.
      return null;
    }
    $file = $this->getErrorLogFile();
    if (!is_file($file)) {
      return null;
    }
    $contents = trim((string) file_get_contents($file));
    return $contents !== '' ? $contents : null;
  }

  /**
   * Gets the path to the error log of the site under test.
   *
   * @return string
   *   Path to the error.log file.
   */
  protected function getErrorLogFile(): string {
    return DRUPAL_ROOT . '/' . $this->siteDirectory . '/error.log';
  }

}
