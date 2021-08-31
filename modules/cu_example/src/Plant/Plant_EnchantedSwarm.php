<?php

declare(strict_types=1);

namespace Drupal\cu_example\Plant;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Donquixote\ObCK\Text\Text;
use Drupal\cu_example\Animal\AnimalInterface;

/**
 * Plant composed of a swarm of animals.
 */
class Plant_EnchantedSwarm implements PlantInterface {

  /**
   * @ocui("enchantedSwarm", "Enchanted swarm")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'animals',
        new Formula_Sequence_ItemLabelT(
          Formula::iface(AnimalInterface::class),
          Text::t('New animal'),
          Text::t('Animal !n')),
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
