<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Tests\Fixtures;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Logs a message or triggers a PHP warning, as requested in the query string.
 *
 * Query parameters:
 * - log_level: PSR-3 log level, e.g. 'error'.
 * - log_channel: Log channel.
 * - log_message: Log message.
 * - php_warning: Message for a PHP warning to trigger.
 *
 * If any of these is present, the request gets a fixed response, which
 * confirms to the test that this subscriber was called. Otherwise, the request
 * is not changed.
 */
class LogOnRequest implements EventSubscriberInterface {

  /**
   * Response text that confirms that this subscriber was called.
   */
  public const RESPONSE_TEXT = 'LogOnRequest: done';

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   Logger factory.
   */
  public function __construct(
    private readonly LoggerChannelFactoryInterface $loggerFactory,
  ) {}

  /**
   * {@inheritdoc}
   *
   * The priority must be higher than that of dynamic page cache (27), which
   * would otherwise respond first: The front page is already visited, and
   * cached, during the test setup, and the query string is not part of the
   * cache key.
   *
   * @see \Drupal\dynamic_page_cache\EventSubscriber\DynamicPageCacheSubscriber::getSubscribedEvents()
   * @see \Drupal\Tests\BrowserTestBase::initFrontPage()
   */
  public static function getSubscribedEvents(): array {
    return [KernelEvents::REQUEST => ['onRequest', 100]];
  }

  /**
   * Logs or triggers a warning, and sets a response.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The event.
   */
  public function onRequest(RequestEvent $event): void {
    $query = $event->getRequest()->query;
    $level = $query->getString('log_level');
    $warning = $query->getString('php_warning');
    if ($level === '' && $warning === '') {
      return;
    }
    if ($level !== '') {
      $this->loggerFactory
        ->get($query->getString('log_channel'))
        ->log($level, $query->getString('log_message'));
    }
    if ($warning !== '') {
      trigger_error($warning, E_USER_WARNING);
    }
    $event->setResponse(new Response(self::RESPONSE_TEXT));
  }

}
