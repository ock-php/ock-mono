<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Optional\Formula_Optional;
use Donquixote\Ock\Formula\Optional\Formula_Optional_Null;
use Donquixote\Ock\Formula\Select\Formula_Select_Fixed;
use Donquixote\Ock\Text\Text;
use Drupal\renderkit\Util\UtilBase;

final class Formula_TagName extends UtilBase {

  /**
   * @Formula("renderkit.tagName.free")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createFree(): FormulaInterface {
    return new Formula_TagNameFree();
  }

  /**
   * @Formula("renderkit.tagName.title")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createForTitle(): FormulaInterface {
    return self::create(['h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'], 'h2');
  }

  /**
   * @Formula("renderkit.tagName.container")
   *
   * @param string $default
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createForContainer($default = 'div'): FormulaInterface {
    return self::create(['div', 'span', 'article', 'section', 'pre'], $default);
  }

  /**
   * @Formula("renderkit.tagName.list")
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createForHtmlList(): FormulaInterface {

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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createOptional(array $allowedTagNames): FormulaInterface {
    $optionsFlat = [];
    foreach ($allowedTagNames as $tagName) {
      $optionsFlat[$tagName] = Text::s($tagName);
    }
    $formula = Formula_Select_Fixed::createFlat($optionsFlat);

    $formula = new Formula_Optional_Null($formula);

    return $formula;
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function create(array $allowedTagNames, $defaultTagName = NULL): FormulaInterface {
    $optionsFlat = [];
    foreach ($allowedTagNames as $tagName) {
      $optionsFlat[$tagName] = Text::s($tagName);
    }
    $formula = Formula_Select_Fixed::createFlat($optionsFlat);
    if (NULL !== $defaultTagName) {
      $formula = (new Formula_Optional($formula))
        ->withEmptySummary(Text::s($defaultTagName));
    }
    return $formula;
  }

}
