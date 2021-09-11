<?php
declare(strict_types=1);

namespace Drupal\ock\Controller;

use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ock\Form\Form_RebuildConfirm;
use Drupal\routelink\RouteModifier\RouteMenuLink;

/**
 * @RouteIsAdmin
 * @RouteRequirePermission("administer ock preset")
 */
class Controller_DevelOps extends ControllerBase {

  /**
   * @Route("/devel/ock/clear")
   * @RouteMenuLink("Rediscover Composition Plugins", menu_name = "devel")
   */
  public function clear(): array {
    return $this->formBuilder()->getForm(Form_RebuildConfirm::class);
  }

}
