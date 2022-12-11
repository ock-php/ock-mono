<?php

declare(strict_types=1);

namespace Drupal\ock_example\Animal;

use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('elephant', 'Elephant')]
class Animal_Elephant implements AnimalInterface {

}
