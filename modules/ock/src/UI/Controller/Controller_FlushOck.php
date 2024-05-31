<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteMenuLink;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\DI\ContainerInjectionViaAttributesTrait;
use Ock\DID\Attribute\Parameter\GetService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

#[RouteIsAdmin]
#[RouteRequirePermission('administer site configuration')]
class Controller_FlushOck extends ControllerBase {

  use ContainerInjectionViaAttributesTrait;

  public function __construct(
    #[GetService('request_stack')]
    private readonly RequestStack $requestStack,
  ) {}

  #[Route('/admin/flush/ock')]
  #[RouteMenuLink('Flush Ock plugins', menu_name: 'admin')]
  public function clear(): RedirectResponse {
    // @todo Actually clear the caches.
    $this->messenger()->addMessage(
      $this->t('Ock cache cleared.'),
    );
    return $this->reloadPage();
  }

  /**
   * Reload the previous page.
   */
  public function reloadPage(): RedirectResponse {
    $referer = $this->requestStack->getCurrentRequest()
      ?->server?->get('HTTP_REFERER')
      ?? \base_path();
    return new RedirectResponse($referer);
  }

}
