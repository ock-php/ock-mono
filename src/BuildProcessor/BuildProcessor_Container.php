<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\Configurator\Configurator_TagName;
use Drupal\renderkit\Html\HtmlAttributesInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

class BuildProcessor_Container implements HtmlAttributesInterface, BuildProcessorInterface {

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
      Configurator_TagName::createFree(),
      Configurator_ClassAttribute::create(),
    );
    $labels = array(
      t('Tag name'),
      t('Classes'),
    );
    return Configurator_CallbackConfigurable::createFromClassStaticMethod(__CLASS__, 'create', $configurators, $labels);
  }

  /**
   * @param string $tagName
   * @param array $classes
   *
   * @return \Drupal\renderkit\BuildProcessor\BuildProcessor_Container
   */
  public static function create($tagName = 'div', array $classes = array()) {
    return (new self())
      ->setTagName($tagName)
      ->addClasses($classes);
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  public function process(array $build) {
    return $this->buildContainer() + array('content' => $build);
  }
}
