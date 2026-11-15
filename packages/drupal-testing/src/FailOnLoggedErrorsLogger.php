<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Psr\Log\LoggerInterface;

/**
 * Logger that copies errors from the Drupal log to the PHP error log.
 *
 * It has two jobs:
 * - Filter: Only entries that should fail a test are copied, see isError().
 * - Write: Entries are written with error_log(). In a test, the PHP error log
 *   of the site under test is the error.log file in the test site directory,
 *   which FailOnLoggedErrorsTrait checks.
 *
 * @see \Drupal\Core\DrupalKernel::setupDrupalTestInChildSite()
 * @see \Ock\DrupalTesting\FailOnLoggedErrorsTrait
 */
class FailOnLoggedErrorsLogger implements LoggerInterface {

  use RfcLoggerTrait;

  /**
   * Severity level names, without the translation from RfcLogLevel.
   *
   * @see \Drupal\Core\Logger\RfcLogLevel::getLevels()
   */
  private const LEVEL_NAMES = [
    RfcLogLevel::EMERGENCY => 'emergency',
    RfcLogLevel::ALERT => 'alert',
    RfcLogLevel::CRITICAL => 'critical',
    RfcLogLevel::ERROR => 'error',
    RfcLogLevel::WARNING => 'warning',
    RfcLogLevel::NOTICE => 'notice',
    RfcLogLevel::INFO => 'info',
    RfcLogLevel::DEBUG => 'debug',
  ];

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
   *   Log message parser.
   */
  public function __construct(
    private readonly LogMessageParserInterface $parser,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function log($level, string|\Stringable $message, array $context = []): void {
    $channel = $context['channel'] ?? '';
    // The logger channel already converts PSR-3 string levels to integers.
    // @see \Drupal\Core\Logger\LoggerChannel::log()
    if (!is_int($level) || !$this->isError($level, $channel)) {
      return;
    }
    $message = (string) $message;
    $variables = $this->parser->parseMessagePlaceholders($message, $context);
    $replacements = [];
    foreach ($variables as $key => $value) {
      if (is_scalar($value) || $value instanceof \Stringable) {
        $replacements[$key] = (string) $value;
      }
    }
    $message = strtr($message, $replacements);
    $message = html_entity_decode(strip_tags($message), ENT_QUOTES);
    error_log(sprintf(
      'Drupal log: [%s] %s: %s',
      self::LEVEL_NAMES[$level] ?? $level,
      $channel,
      $message,
    ));
  }

  /**
   * Determines whether a log entry should be reported as an error.
   *
   * @param int $level
   *   Severity level, one of the RfcLogLevel constants.
   * @param string $channel
   *   Log channel.
   *
   * @return bool
   *   true if the log entry should be copied to the PHP error log.
   */
  private function isError(int $level, string $channel): bool {
    if ($channel === 'php') {
      // Include php warnings and notices, but not deprecations, which are
      // logged with severity 'debug'.
      return $level <= RfcLogLevel::NOTICE;
    }
    // From other channels, include errors and worse, but not e.g. warnings for
    // 'page not found' or 'access denied'.
    return $level <= RfcLogLevel::ERROR;
  }

}
