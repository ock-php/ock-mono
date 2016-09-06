<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\Configurator\Configurator_TagName;
use Drupal\renderkit\Html\HtmlTagInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

class ListFormat_Container implements ListFormatInterface, HtmlTagInterface {

  use HtmlTagTrait;

  /**
   * @CfrPlugin(
   *   id = "container",
   *   label = @t("Container")
   * )
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {
    $configurators = array(
      Configurator_TagName::createForContainer(),
      Configurator_ClassAttribute::create(),
    );
    $labels = array(
      t('List type'),
      t('Classes'),
    );
    return Configurator_CallbackConfigurable::createFromClassStaticMethod(__CLASS__, 'create', $configurators, $labels);
  }

  /**
   * @param string $tagName
   * @param string[] $classes
   *
   * @return \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  public static function create($tagName, array $classes) {
    return (new self())
      ->setTagName($tagName)
      ->addClasses($classes);
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds) {
    return $this->buildContainer() + $builds;
  }
}
