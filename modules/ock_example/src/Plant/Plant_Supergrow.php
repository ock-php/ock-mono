<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

/**
 * @ock("supergrow", "Supergrow", decorator = true)
 */
class Plant_Supergrow implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_example\Plant\PlantInterface $decorated
   */
  public function __construct(PlantInterface $decorated) {}

}
