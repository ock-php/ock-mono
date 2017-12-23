<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional_Null;
use Donquixote\Cf\Schema\Select\CfSchema_Select_Fixed;
use Drupal\renderkit8\Util\UtilBase;

final class CfSchema_TagName extends UtilBase {

  /**
   * @CfSchema("renderkit8.tagName.free")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createFree() {
    return new CfSchema_TagNameFree();
  }

  /**
   * @CfSchema("renderkit8.tagName.title")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createForTitle() {
    return self::create(['h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'], 'h2');
  }

  /**
   * @CfSchema("renderkit8.tagName.container")
   *
   * @param string $default
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createForContainer($default = 'div') {
    return self::create(['div', 'span', 'article', 'section', 'pre'], $default);
  }

  /**
   * @CfSchema("renderkit8.tagName.list")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createForHtmlList() {

    $optionsFlat = [
      'ul' => t('Unordered list (ul)'),
      'ol' => t('Ordered list (ol)'),
    ];

    // @todo Make 'ul' a default id?
    return CfSchema_Select_Fixed::createFlat($optionsFlat);
  }

  /**
   * @param string[] $allowedTagNames
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createOptional(array $allowedTagNames) {

    $optionsFlat = array_combine($allowedTagNames, $allowedTagNames);

    $schema = CfSchema_Select_Fixed::createFlat($optionsFlat);

    $schema = new CfSchema_Optional_Null($schema);

    return $schema;
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create(array $allowedTagNames, $defaultTagName = NULL) {

    $optionsFlat = array_combine($allowedTagNames, $allowedTagNames);

    $schema = CfSchema_Select_Fixed::createFlat($optionsFlat);

    if (NULL !== $defaultTagName) {
      $schema = (new CfSchema_Optional($schema))
        ->withEmptyValue($defaultTagName)
        ->withEmptySummary($defaultTagName);
    }

    return $schema;
  }

}
