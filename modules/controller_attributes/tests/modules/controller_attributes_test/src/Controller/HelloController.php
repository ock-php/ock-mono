<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteDefaults;
use Drupal\controller_attributes\Attribute\RouteHttpMethod;
use Drupal\controller_attributes\Attribute\RouteIsAdmin;
use Drupal\controller_attributes\Attribute\RouteMenuLink;
use Drupal\controller_attributes\Attribute\RouteOptions;
use Drupal\controller_attributes\Attribute\RouteParameters;
use Drupal\controller_attributes\Attribute\RouteRequirements;
use Drupal\controller_attributes\Controller\ControllerRouteNameInterface;
use Drupal\controller_attributes\Controller\ControllerRouteNameTrait;

#[Route('/controller-attributes-test')]
class HelloController implements ControllerRouteNameInterface {

  use ControllerRouteNameTrait;

  public function methodWithoutAttributes(): array {
    return [];
  }

  /**
   * This route will be ignored.
   */
  #[Route('/not-installed')]
  #[RouteRequirements(['_module_dependencies' => 'not_installed'])]
  public function notInstalled(): array {
    return [];
  }

  #[Route('/post-or-put/{arg}')]
  #[RouteHttpMethod('POST', 'PUT')]
  #[RouteIsAdmin]
  #[RouteDefaults(['x' => 'X'])]
  #[RouteOptions(['a' => 'B'])]
  #[RouteRequirements(['_module_dependencies' => 'user'])]
  #[RouteParameters(['arg' => ['type' => 'entity:user']])]
  public function postOrPut(): array {
    return [];
  }

  #[RouteMenuLink]
  #[Route('/hello')]
  public function hello(): array {
    return [
      '#markup' =>  'Hello',
      // Prevent a method from being seen as unused.
      'sub_element' => $this->helloPrivate(),
    ];
  }

  #[RouteMenuLink('Goodbye', 'A farewell greeting')]
  #[Route('/goodbye')]
  public function goodbye(): array {
    return [];
  }

  #[Route('/non-public-methods-are-ignored')]
  private function helloPrivate(): array {
    return [];
  }

}
