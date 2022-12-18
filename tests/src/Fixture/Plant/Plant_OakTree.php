<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Tests\Fixture\Plant;

use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\Ock\Text\Text_Translatable;

class Plant_OakTree implements PlantInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(self::class, "sequence", "Sequence")]
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'height',
        new Text_Translatable('Height in meters'),
        new Formula_Textfield_IntegerInRange(0, 100),
      )
      ->construct(self::class);
  }

  /**
   * @param int $height
   */
  public function __construct(
    private readonly int $height,
  ) {}

  /**
   * @return int
   */
  public function getHeight(): int {
    return $this->height;
  }

}
