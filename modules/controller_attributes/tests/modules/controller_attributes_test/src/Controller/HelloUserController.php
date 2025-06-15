<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\Component\Render\MarkupInterface;
use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteAccessPublic;
use Drupal\controller_attributes\Attribute\RouteDefaultTaskLink;
use Drupal\controller_attributes\Attribute\RouteTaskLink;
use Drupal\controller_attributes\Attribute\RouteTitle;
use Drupal\controller_attributes\Attribute\RouteTitleMethod;
use Drupal\user\UserInterface;

#[Route('/hello/user/{user}')]
class HelloUserController {

  public function title(UserInterface $user): MarkupInterface|string {
    return $user->label();
  }

  #[Route('')]
  #[RouteDefaultTaskLink('View')]
  #[RouteAccessPublic]
  #[RouteTitleMethod([self::class, 'title'])]
  public function view(UserInterface $user): array {
    return ['#markup' => __METHOD__ . '(' . $user->label() . ')'];
  }

  #[Route('/edit')]
  #[RouteTitle('Edit')]
  #[RouteTaskLink('Edit', base_route: 'controller_attributes_test.hello_user.view')]
  #[RouteAccessPublic]
  public function edit(UserInterface $user): array {
    return ['#markup' => __METHOD__ . '(' . $user->label() . ')'];
  }

  #[Route('/invite')]
  #[RouteTitle('Invite')]
  #[RouteTaskLink('Invite')]
  #[RouteAccessPublic]
  public function invite(UserInterface $user): array {
    return ['#markup' => __METHOD__ . '(' . $user->label() . ')'];
  }

  #[Route('/delete-different-base-route')]
  #[RouteTaskLink('Edit', base_route: 'entity.user.canonical')]
  #[RouteAccessPublic]
  public function deleteUserDifferentBaseRoute(UserInterface $user): array {
    return ['#markup' => __METHOD__ . '(' . $user->label() . ')'];
  }

}
