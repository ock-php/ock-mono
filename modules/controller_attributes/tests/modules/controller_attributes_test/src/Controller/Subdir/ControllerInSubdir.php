<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller\Subdir;

use Drupal\controller_attributes\Attribute\Route;

#[Route('/controller-attributes-test/subdir')]
class ControllerInSubdir {

  #[Route('/hello-in-subdir')]
  public function hello(): void {}

}
