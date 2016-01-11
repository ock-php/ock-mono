<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\Html\HtmlAttributesTrait;
use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;

/**
 * Builds a render array for ul/li or ol/li lists.
 */
class ListFormat_HtmlList implements ListFormatInterface {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @var mixed[]
   */
  private $itemAttributes = array();

  /**
   * @CfrPlugin(
   *   id = "htmlList",
   *   label = @t("HTML list")
   * )
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createConfigurator() {
    $configurators = array(
      Configurator_LegendSelect::createFromOptions(
        array(
          'ul' => t('Unordered list (ul)'),
          'ol' => t('Ordered list (ol)'),
        ),
        'ul'),
      new Configurator_ClassAttribute(),
    );
    $labels = array(t('List type'), t('Classes'));
    $cfr = Configurator_CallbackConfigurable::createFromClassStaticMethod(__CLASS__, 'create', $configurators, $labels);
    return $cfr;
  }

  /**
   * @param string $tagName
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  static function create($tagName = 'ul', array $classes = array()) {
    $format = new self($tagName);
    foreach ($classes as $class) {
      $format->addClass($class);
    }
    return $format;
  }

  /**
   * @param string $tagName
   */
  function __construct($tagName = 'ul') {
    $this->tagName = $tagName;
  }

  /**
   * @param string $class
   *
   * @return $this
   */
  function addItemClass($class) {
    $this->itemAttributes['class'][] = $class;
    return $this;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    $listBuild = array(
      '#theme' => 'themekit_item_list',
      '#tag_name' => $this->tagName,
      '#attributes' => $this->attributes,
      '#item_attributes' => $this->itemAttributes,
    );
    foreach ($builds as $delta => $build) {
      $listBuild[$delta]['content'] = $build;
    }
    return $listBuild;
  }
}
