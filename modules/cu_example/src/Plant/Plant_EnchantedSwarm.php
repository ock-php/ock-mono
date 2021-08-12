<?php

declare(strict_types=1);

namespace Drupal\cu_example\Plant;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Text\Text;
use Drupal\cu_example\Animal\AnimalInterface;

/**
 * Plant composed of a swarm of animals.
 */
class Plant_EnchantedSwarm implements PlantInterface {

  /**
   * @ocui("enchantedSwarm", "Enchanted swarm")
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'animals',
        Formula::ifaceSequence(AnimalInterface::class),
        Text::t('Animals'))
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\cu_example\Animal\AnimalInterface[] $animals
   *   Enchanted animal.
   */
  public function __construct(array $animals) {}

}
