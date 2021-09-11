<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Formula\Formula_ListFormat_Expert;

class ListFormat_ElementDefaults implements ListFormatInterface {

  /**
   * @var array
   */
  private $elementDefaults;

  /**
   * @CfrPlugin("expert", "Expert")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createExpertFormula() {
    return new Formula_ListFormat_Expert();
  }

  /**
   * @param array $elementDefaults
   */
  public function __construct(array $elementDefaults) {
    $this->elementDefaults = $elementDefaults;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds): array {
    return $builds + $this->elementDefaults;
  }
}
