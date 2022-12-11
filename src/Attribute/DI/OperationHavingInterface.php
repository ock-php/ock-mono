<?php

declare(strict_types=1);

namespace Drupal\ock\Attribute\DI;

interface OperationHavingInterface {

  public function getOp(): string;

}
