<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Drupal\ock_example\Animal\AnimalInterface;

/**
 * @ock("enchantedCreature", "Enchanted creature", inline = true)
 */
class Plant_EnchantedCreature implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_example\Animal\AnimalInterface $enchanted_animal
   *   Enchanted animal.
   */
  public function __construct(AnimalInterface $enchanted_animal) {}

}
