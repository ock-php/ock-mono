<?php

declare(strict_types=1);

namespace Drupal\ock_example\Plant;

use Drupal\ock_example\Animal\AnimalInterface;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Sequence\Formula_Sequence_ItemLabelT;
use Ock\Ock\Text\Text;

/**
 * Plant composed of a swarm of animals.
 */
class Plant_EnchantedSwarm implements PlantInterface {

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, "enchantedSwarm", "Enchanted swarm")]
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'animals',
        Text::t('Animals'),
        new Formula_Sequence_ItemLabelT(
          Formula::iface(AnimalInterface::class),
          Text::t('New animal'),
          Text::t('Animal !n'),
        ),
      )
      ->construct(self::class);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\ock_example\Animal\AnimalInterface[] $animals
   *   Enchanted animal.
   */
  public function __construct(array $animals) {}

}
