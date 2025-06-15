<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes_test\Controller;

use Drupal\controller_attributes\Attribute\Route;

#[Route('/interfaces-are-ignored')]
interface HelloInterface {

}
