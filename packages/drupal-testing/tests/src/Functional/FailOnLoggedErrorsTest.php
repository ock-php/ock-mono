<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Tests\Functional;

use Drupal\Tests\BrowserTestBase;
use Ock\DrupalTesting\FailOnLoggedErrorsLogger;
use Ock\DrupalTesting\FailOnLoggedErrorsTrait;
use Ock\DrupalTesting\TestSiteServicesTrait;
use Ock\DrupalTesting\Tests\Fixtures\LogOnRequest;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;

/**
 * Tests that errors logged in the site under test are detected.
 */
#[CoversClass(FailOnLoggedErrorsLogger::class)]
#[CoversTrait(FailOnLoggedErrorsTrait::class)]
#[CoversTrait(TestSiteServicesTrait::class)]
class FailOnLoggedErrorsTest extends BrowserTestBase {

  use FailOnLoggedErrorsTrait {
    prepareSettings as traitPrepareSettings;
    tearDown as traitTearDown;
  }

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function prepareSettings(): void {
    $this->traitPrepareSettings();
    $this->registerTestSiteService(LogOnRequest::class, [
      'autowire' => true,
      'tags' => [['name' => 'event_subscriber']],
    ]);
  }

  /**
   * {@inheritdoc}
   *
   * Verifies that the trait fails the test, because the test method leaves an
   * error in the error log on purpose.
   *
   * The parent method is called through the aliased trait method.
   *
   * @phpstan-ignore phpunit.callParent
   */
  protected function tearDown(): void {
    try {
      $this->traitTearDown();
    }
    catch (AssertionFailedError $e) {
      $this->assertStringStartsWith("The PHP error log of the site under test is not empty:\n\n", $e->getMessage());
      $this->assertStringContainsString('Drupal log: [error] ock_test: Error left for tear down.', $e->getMessage());
      return;
    }
    $this->fail('Expected a failure in tear down, because of errors in the error log.');
  }

  /**
   * Tests which errors are copied to the error log, and how.
   */
  public function testLoggedErrors(): void {
    // The logger is registered in the container.
    $this->assertTrue($this->container->has(FailOnLoggedErrorsLogger::class));

    // Nothing was logged during the site install.
    $this->assertSame('', $this->readAndClearErrorLog());

    // Log in the test process, to check the message format.
    \Drupal::logger('ock_test')->error('Error with @placeholder and {psr}.', [
      '@placeholder' => 'value <em>with markup</em>',
      'psr' => 'psr-3 value',
    ]);
    $this->assertSame([
      'Drupal log: [error] ock_test: Error with value with markup and psr-3 value.',
    ], $this->getLines($this->readAndClearErrorLog()));

    // Log during a request to the site under test, in a different process.
    // The cases are declared with plain string types. Otherwise PhpStan 2.3.6
    // tracks all combinations of the literal strings in the messages, and
    // reports an 'unresolvable type' in assertSame().
    /** @var list<array{string, string, bool}> $cases */
    $cases = [
      // Errors and worse are copied.
      ['error', 'ock_test', true],
      ['critical', 'ock_test', true],
      // Warnings outside the 'php' channel are not copied.
      ['warning', 'ock_test', false],
      // Notices in the 'php' channel are copied.
      ['notice', 'php', true],
      // Deprecations are logged with severity 'debug', and are not copied.
      ['debug', 'php', false],
    ];
    foreach ($cases as [$level, $channel, $expected]) {
      $message = "Message with level '$level' in channel '$channel'.";
      $this->drupalGet('/', ['query' => [
        'log_level' => $level,
        'log_channel' => $channel,
        'log_message' => $message,
      ]]);
      $this->assertSame(LogOnRequest::RESPONSE_TEXT, $this->getSession()->getPage()->getContent());
      $this->assertSame(
        $expected ? ["Drupal log: [$level] $channel: $message"] : [],
        $this->getLines($this->readAndClearErrorLog()),
        "Level '$level', channel '$channel'.",
      );
    }

    // A real PHP warning in the site under test.
    // In a BrowserTestBase test, core reports this back to the test with a
    // response header, and the http client throws an exception. A WebDriver
    // test does not get this, which is why the error log is checked.
    $exception = null;
    try {
      $this->drupalGet('/', ['query' => ['php_warning' => 'Warning from LogOnRequest.']]);
    }
    catch (\Exception $e) {
      $exception = $e;
    }
    $this->assertNotNull($exception, 'Expected an exception for the PHP warning.');
    $this->assertStringContainsString('Warning from LogOnRequest.', $exception->getMessage());
    // The message includes a backtrace, over several lines.
    $lines = $this->getLines($this->readAndClearErrorLog());
    $this->assertStringStartsWith('Drupal log: [warning] php: User warning: Warning from LogOnRequest.', $lines[0] ?? '');

    // The 'page not found' warning from the site under test is not copied.
    $this->drupalGet('/ock-test-page-that-does-not-exist');
    $this->assertSession()->statusCodeEquals(404);
    $this->assertSame('', $this->readAndClearErrorLog());

    // Leave an error in the error log, to check the failure in tearDown().
    $this->drupalGet('/', ['query' => [
      'log_level' => 'error',
      'log_channel' => 'ock_test',
      'log_message' => 'Error left for tear down.',
    ]]);
    $this->assertSame(LogOnRequest::RESPONSE_TEXT, $this->getSession()->getPage()->getContent());
  }

  /**
   * Reads and clears the PHP error log of the site under test.
   *
   * @return string
   *   Contents of the error log.
   */
  private function readAndClearErrorLog(): string {
    $contents = $this->readErrorLog() ?? '';
    if ($contents !== '') {
      file_put_contents($this->getErrorLogFile(), '');
    }
    return $contents;
  }

  /**
   * Gets error log lines without the timestamp prefix.
   *
   * @param string $contents
   *   Contents of the error log.
   *
   * @return list<string>
   *   Lines without the timestamp prefix.
   */
  private function getLines(string $contents): array {
    if ($contents === '') {
      return [];
    }
    $lines = [];
    foreach (explode("\n", trim($contents)) as $line) {
      $lines[] = (string) preg_replace('/^\[[^\]]*\] /', '', $line);
    }
    return $lines;
  }

}
