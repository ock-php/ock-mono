<?php

declare(strict_types=1);

namespace Drupal\cu_example\Plant;

use Drupal\cu_example\Animal\AnimalInterface;

/**
 * @ocui("enchantedCreature", "Enchanted creature")
 */
class Plant_EnchantedCreature implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\cu_example\Animal\AnimalInterface $enchanted_animal
   *   Enchanted animal.
   */
  public function __construct(AnimalInterface $enchanted_animal) {}

}
