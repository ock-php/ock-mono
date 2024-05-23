<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Drupal\ock_example\Animal\AnimalInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

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
