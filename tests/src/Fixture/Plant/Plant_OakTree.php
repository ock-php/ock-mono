<?php

namespace Donquixote\ObCK\Tests\Fixture\Plant;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\ObCK\Text\Text_Translatable;

class Plant_OakTree implements PlantInterface {

  /**
   * @var int
   */
  private $height;

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
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
