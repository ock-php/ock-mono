<?php
declare(strict_types=1);

namespace Drupal\ock\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\Core\Controller\ControllerBase;
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
