<?php

namespace Donquixote\ObCK\Tests\Fixture\Plant;

use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
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
  public static function formula() {
    return Formula_GroupVal_Callback::fromClass(
      self::class,
      [
        'height' => new Formula_Textfield_IntegerInRange(0, 100),
      ],
      [
        'height' => new Text_Translatable('Height in meters'),
      ]);
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
