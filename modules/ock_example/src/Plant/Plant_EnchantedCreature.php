<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\ock_example\Animal\AnimalInterface;

// @todo Mark as 'inline'?
#[OckPluginInstance('enchantedCreature', 'Enchanted creature')]
class Plant_EnchantedCreature implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_example\Animal\AnimalInterface $enchanted_animal
   *   Enchanted animal.
   */
  public function __construct(AnimalInterface $enchanted_animal) {}

}
