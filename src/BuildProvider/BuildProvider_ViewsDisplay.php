<?php

namespace Drupal\renderkit\BuildProvider;

use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\EnumMap\EnumMap_ViewsDisplayId;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
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
   * @CfrPlugin(
   *   id = "viewsDisplay",
   *   label = @t("Views display")
   * )
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {

    $legend = new EnumMap_ViewsDisplayId();

    return Configurator_CallbackConfigurable::createFromCallable(
      function ($id, LabeledFormatInterface $labeledFormat = NULL) {
        list($view_name, $display_id) = explode(':', $id . ':');
        if ('' === $view_name || '' === $display_id) {
          return NULL;
        }
        // No further checking at this point.
        return new self($view_name, $display_id, $labeledFormat);
      },
      [
        Configurator_LegendSelect::createRequired($legend),
        \cfrplugin()->interfaceGetOptionalConfigurator(
          LabeledEntityBuildProcessorInterface::class),
      ],
      [
        t('Views display'),
        t('Label format'),
      ]);
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
