<?php

namespace Donquixote\Ock\Tests\Fixture\Plant;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\Ock\Text\Text_Translatable;

class Plant_OakTree implements PlantInterface {

  /**
   * @var int
   */
  private $height;

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'height',
        new Formula_Textfield_IntegerInRange(0, 100),
        new Text_Translatable('Height in meters'))
      ->construct(self::class);
  }

  /**
   * @param int $height
   */
  public function __construct(int $height) {
    $this->height = $height;
  }

  /**
   * @return int
   */
  public function getHeight(): int {
    return $this->height;
  }

}
