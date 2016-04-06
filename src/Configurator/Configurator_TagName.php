<?php

namespace Drupal\renderkit\Configurator;

use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\renderkit\Util\UtilBase;

final class Configurator_TagName extends UtilBase {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createForTitle() {
    return self::create(array('h1', 'h2', 'h3', 'h4', 'h5', 'label', 'strong'), 'h2');
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createForContainer() {
    return self::create(array('div', 'span', 'article', 'section', 'pre'), 'div');
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createForHtmlList() {
    return Configurator_LegendSelect::createFromOptions(array(
      'ul' => t('Unordered list (ul)'),
      'ol' => t('Ordered list (ol)'),
    ));
  }

  /**
   * @param string[] $allowedTagNames
   * @param string|null $defaultTagName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function create(array $allowedTagNames, $defaultTagName) {
    return Configurator_LegendSelect::createFromOptions(
      array_combine($allowedTagNames, $allowedTagNames),
      $defaultTagName);
  }

  /**
   * @return \Drupal\renderkit\Configurator\Configurator_TagNameFree
   */
  static function createFree() {
    return new Configurator_TagNameFree();
  }

}
