<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Optional\Formula_Optional;
use Donquixote\ObCK\Formula\Optional\Formula_Optional_Null;
use Donquixote\ObCK\Formula\Select\Formula_Select_Fixed;
use Drupal\renderkit\Util\UtilBase;

final class Formula_TagName extends UtilBase {

  /**
   * @Formula("renderkit.tagName.free")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createFree() {
    return new Formula_TagNameFree();
  }

  /**
   * @Formula("renderkit.tagName.title")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createForTitle() {
    return self::create(['h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'], 'h2');
  }

  /**
   * @Formula("renderkit.tagName.container")
   *
   * @param string $default
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createForContainer($default = 'div') {
    return self::create(['div', 'span', 'article', 'section', 'pre'], $default);
  }

  /**
   * @Formula("renderkit.tagName.list")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createForHtmlList() {

    $optionsFlat = [
      'ul' => t('Unordered list (ul)'),
      'ol' => t('Ordered list (ol)'),
    ];

    // @todo Make 'ul' a default id?
    return Formula_Select_Fixed::createFlat($optionsFlat);
  }

  /**
   * @param string[] $allowedTagNames
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createOptional(array $allowedTagNames) {

    $optionsFlat = array_combine($allowedTagNames, $allowedTagNames);

    $formula = Formula_Select_Fixed::createFlat($optionsFlat);

    $formula = new Formula_Optional_Null($formula);

    return $formula;
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create(array $allowedTagNames, $defaultTagName = NULL) {

    $optionsFlat = array_combine($allowedTagNames, $allowedTagNames);

    $formula = Formula_Select_Fixed::createFlat($optionsFlat);

    if (NULL !== $defaultTagName) {
      $formula = (new Formula_Optional($formula))
        ->withEmptyValue($defaultTagName)
        ->withEmptySummary($defaultTagName);
    }

    return $formula;
  }

}
