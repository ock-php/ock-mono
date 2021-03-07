<?php

namespace Donquixote\OCUI\Tests\Fixture\Plant;

use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\OCUI\Formula\Textfield\Formula_Textfield_IntegerInRange;
use Donquixote\OCUI\Text\Text_Translatable;

class Plant_OakTree implements PlantInterface {

  /**
   * @var int
   */
  private $height;

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
