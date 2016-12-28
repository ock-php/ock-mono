<?php

namespace Drupal\renderkit\BuildProvider;

use Drupal\cfrapi\Configurator\Group\Configurator_GroupWithValueCallback;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\Configurator\Id\Configurator_ViewsDisplayId;
use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;

class BuildProvider_ViewsDisplay implements BuildProviderInterface {

  /**
   * @var string
   */
  private $viewName;

  /**
   * @var string
   */
  private $displayId;

  /**
   * @var \Drupal\renderkit\LabeledFormat\LabeledFormatInterface
   */
  private $labeledFormat;

  /**
   * @CfrPlugin("viewsDisplay", @t("Views display"))
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {

    return Configurator_CallbackConfigurable::createFromClassStaticMethod(
      self::class,
      /* @see doCreate() */
      'doCreate',
      [
        new Configurator_ViewsDisplayId(),
        \cfrplugin()->interfaceGetOptionalConfigurator(
          LabeledFormatInterface::class),
      ],
      [
        t('Views display'),
        t('Label format'),
      ]);
  }

  /**
   * @param string $viewNameWithDisplayId
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface $labeledFormat
   *
   * @return \Drupal\renderkit\BuildProvider\BuildProvider_ViewsDisplay|null
   */
  public static function doCreate($viewNameWithDisplayId, LabeledFormatInterface $labeledFormat = NULL) {
    list($view_name, $display_id) = explode(':', $viewNameWithDisplayId . ':');
    if ('' === $view_name || '' === $display_id) {
      return NULL;
    }
    // No further checking at this point.
    return new self($view_name, $display_id, $labeledFormat);

  }

  /**
   * @param string $viewName
   * @param string $displayId
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface $labeledFormat
   */
  public function __construct($viewName, $displayId, LabeledFormatInterface $labeledFormat = NULL) {
    $this->viewName = $viewName;
    $this->displayId = $displayId;
    $this->labeledFormat = $labeledFormat;
  }

  /**
   * @return array
   *   A render array.
   */
  public function build() {
    $view = \views_get_view($this->viewName);
    if (NULL === $view) {
      return [];
    }
    $success = $view->set_display($this->displayId);
    if (FALSE === $success) {
      return [];
    }
    $markup = $view->preview();
    if (FALSE === $markup) {
      return [];
    }
    $build = ['#markup' => $markup];
    if (NULL === $this->labeledFormat) {
      return $build;
    }
    $label = $view->get_title();
    if (empty($label)) {
      return $build;
    }
    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
