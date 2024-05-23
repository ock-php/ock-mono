<?php
declare(strict_types=1);

namespace Drupal\ock\UI\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ock\Attribute\Routing\Route;
use Drupal\ock\Attribute\Routing\RouteIsAdmin;
use Drupal\ock\Attribute\Routing\RouteMenuLink;
use Drupal\ock\Attribute\Routing\RouteRequirePermission;
use Drupal\ock\UI\Form\Form_RebuildConfirm;

#[RouteIsAdmin]
#[RouteRequirePermission('administer ock preset')]
class Controller_DevelOps extends ControllerBase {

  #[Route('/devel/ock/clear')]
  #[RouteMenuLink('Rediscover Composition Plugins', menu_name: 'devel')]
  public function clear(): array {
    return $this->formBuilder()->getForm(Form_RebuildConfirm::class);
  }

}
