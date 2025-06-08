<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;
use Drupal\controller_attributes\Attribute\RouteMenuLink;

#[Route('/controller-attributes-test')]
class HelloController {

  public function methodWithoutAttributes(): array {
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
