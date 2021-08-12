<?php
declare(strict_types=1);

namespace Drupal\cu\Controller;

use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\Configuration\RouteIsAdmin;
use Drupal\controller_annotations\Configuration\RouteRequirePermission;
use Drupal\Core\Controller\ControllerBase;
use Drupal\cu\Form\Form_RebuildConfirm;
use Drupal\routelink\RouteModifier\RouteMenuLink;

/**
 * @RouteIsAdmin
 * @RouteRequirePermission("administer cu preset")
 */
class Controller_DevelOps extends ControllerBase {

  /**
   * @Route("/devel/cu/clear")
   * @RouteMenuLink("Rediscover Composition Plugins", menu_name = "devel")
   */
  public function clear(): array {
    return $this->formBuilder()->getForm(Form_RebuildConfirm::class);
  }

}
