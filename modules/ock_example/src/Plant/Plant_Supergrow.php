<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;

// @todo Mark as 'decorator'.
#[OckPluginInstance('supergrow', 'Supergrow')]
class Plant_Supergrow implements PlantInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock_example\Plant\PlantInterface $decorated
   */
  public function __construct(PlantInterface $decorated) {}

}
