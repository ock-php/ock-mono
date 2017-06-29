<?php

namespace Drupal\renderkit\BuildProcessor;

use Drupal\cfrapi\CfrSchema\Group\GroupSchema_Callback;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\Configurator\Configurator_ClassAttribute;
use Drupal\renderkit\Configurator\Configurator_TagName;
use Drupal\renderkit\Html\HtmlAttributesInterface;
use Drupal\renderkit\Html\HtmlTagTrait;

class BuildProcessor_Container implements HtmlAttributesInterface, BuildProcessorInterface {

  use HtmlTagTrait;

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public static function createSchema() {

    $schemas = [
      Configurator_TagName::createFree(),
      Configurator_ClassAttribute::create(),
    ];

    $labels = [
      t('Tag name'),
      t('Classes'),
    ];

    return GroupSchema_Callback::createFromClassStaticMethod(
      __CLASS__,
      'create',
      $schemas,
      $labels);
  }

  /**
   * @CfrPlugin(
   *   id = "container",
   *   label = @t("Container")
   * )
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {
    $configurators = [
      Configurator_TagName::createFree(),
      Configurator_ClassAttribute::create(),
    ];
    $labels = [
      t('Tag name'),
      t('Classes'),
    ];
    return Configurator_CallbackConfigurable::createFromClassStaticMethod(__CLASS__, 'create', $configurators, $labels);
  }

  /**
   * @param string $tagName
   * @param array $classes
   *
   * @return \Drupal\renderkit\BuildProcessor\BuildProcessor_Container
   */
  public static function create($tagName = 'div', array $classes = []) {
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
    return $this->buildContainer() + ['content' => $build];
  }
}
