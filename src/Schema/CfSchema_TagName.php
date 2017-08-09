<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Options\CfSchema_Options_Fixed;
use Drupal\renderkit\Util\UtilBase;

final class CfSchema_TagName extends UtilBase {

  /**
   * @CfSchema("renderkit.tagName.free")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createFree() {
    return new CfSchema_TagNameFree();
  }

  /**
   * @CfSchema("renderkit.tagName.title")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createForTitle() {
    return self::create(['h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'], 'h2');
  }

  /**
   * @CfSchema("renderkit.tagName.container")
   *
   * @param string $default
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createForContainer($default = 'div') {
    return self::create(['div', 'span', 'article', 'section', 'pre'], $default);
  }

  /**
   * @CfSchema("renderkit.tagName.list")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createForHtmlList() {

    $optionsFlat = [
      'ul' => t('Unordered list (ul)'),
      'ol' => t('Ordered list (ol)'),
    ];

    // @todo Make 'ul' a default id?
    return CfSchema_Options_Fixed::createFlat($optionsFlat);
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create(array $allowedTagNames, $defaultTagName = NULL) {

    $optionsFlat = array_combine($allowedTagNames, $allowedTagNames);

    $schema = CfSchema_Options_Fixed::createFlat($optionsFlat);

    if (NULL !== $defaultTagName) {
      $schema = (new CfSchema_Optional($schema))
        ->withEmptyValue($defaultTagName)
        ->withEmptySummary($defaultTagName);
    }

    return $schema;
  }

}
