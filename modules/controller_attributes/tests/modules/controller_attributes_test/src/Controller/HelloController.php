<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;

#[Route('/controller-attributes-test')]
class HelloController {

  #[Route('/hello')]
  public function hello(): array {
    return [
      '#markup' =>  'Hello',
    ];
  }

}
