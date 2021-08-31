<?php

declare(strict_types=1);

namespace Drupal\cu_example\Plant;

/**
 * @ocui("supergrow", "Supergrow", decorator = true)
 */
class Plant_Supergrow implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\cu_example\Plant\PlantInterface $decorated
   */
  public function __construct(PlantInterface $decorated) {}

}
