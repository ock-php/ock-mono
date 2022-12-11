<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelectFlat;

use Donquixote\Ock\Formula\Id\Formula_IdInterface;

interface Formula_DrupalSelectFlatInterface extends Formula_IdInterface {

  public function getOptions(): array;

}
