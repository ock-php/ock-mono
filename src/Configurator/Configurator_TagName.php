<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\Id\Configurator_FlatOptionsSelect;
use Drupal\renderkit\Util\UtilBase;

final class Configurator_TagName extends UtilBase {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createForTitle() {
    return self::create(['h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'], 'h2');
  }

  /**
   * @param string $default
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createForContainer($default = 'div') {
    return self::create(['div', 'span', 'article', 'section', 'pre'], $default);
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createForHtmlList() {
    return new Configurator_FlatOptionsSelect(
      [
      'ul' => t('Unordered list (ul)'),
      'ol' => t('Ordered list (ol)'),
      ]
    );
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function create(array $allowedTagNames, $defaultTagName) {
    return Configurator_FlatOptionsSelect::createRequired(
      array_combine($allowedTagNames, $allowedTagNames),
      $defaultTagName);
  }

  /**
   * @return \Drupal\renderkit\Configurator\Configurator_TagNameFree
   */
  public static function createFree() {
    return new Configurator_TagNameFree();
  }

}
