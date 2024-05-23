<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('potato', 'Potato')]
class Plant_Potato implements PlantInterface {

  public function __construct() {}

}
