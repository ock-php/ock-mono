<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteActionLink;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteTitle;

class ParentChildController {

  #[Route('/parent')]
  #[RouteMenuLink('Parent')]
  public function parent(): array { return []; }

  #[Route('/parent/auto-child')]
  #[RouteMenuLink('Parent')]
  public function automaticChild(): array { return []; }

  #[Route('/explicit-child')]
  #[RouteMenuLink('Parent', parent: 'controller_attributes_test.parent_child.parent')]
  public function explicitChild(): array { return []; }

}
