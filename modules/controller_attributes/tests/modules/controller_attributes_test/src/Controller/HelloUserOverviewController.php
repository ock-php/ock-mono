<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteActionLink;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteRequirePermission;
use Drupal\controller_attributes\Attribute\RouteTitle;

#[Route('/hello/user')]
class HelloUserOverviewController {

  #[Route]
  #[RouteTitle('List of users')]
  #[RouteMenuLink('List of users')]
  #[RouteRequirePermission('administer users')]
  public function overview(): array { return []; }

  #[Route('/add-user')]
  #[RouteTitle('Add user')]
  #[RouteRequirePermission('administer users')]
  #[RouteActionLink('Add user', ['controller_attributes_test.hello_user_overview.overview'])]
  public function add(): array { return []; }

  #[Route('/invite-user')]
  #[RouteTitle('Invite user')]
  #[RouteRequirePermission('administer users')]
  #[RouteActionLink('Invite user', ['controller_attributes_test.hello_user_overview.overview'], weight: 10)]
  public function invite(): array { return []; }

}
