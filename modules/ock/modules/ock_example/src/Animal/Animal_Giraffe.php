<?php

declare(strict_types=1);

namespace Drupal\ock_example\Animal;

use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('giraffe', 'Giraffe')]
class Animal_Giraffe implements AnimalInterface {

}
