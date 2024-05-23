<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
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
  public function __construct(
    #[OckOption('animal', 'Animal')]
    AnimalInterface $enchanted_animal,
  ) {}

}
