<?php

namespace Drupal\renderkit8\BuildProvider;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\LabeledFormat\LabeledFormatInterface;
use Drupal\renderkit8\Schema\CfSchema_ViewsDisplayId;

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
   * @var \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface
   */
  private $labeledFormat;

  /**
   * @CfrPlugin("viewsDisplay", @t("Views display"))
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'doCreate',
      [
        new CfSchema_ViewsDisplayId(),
        CfSchema_IfaceWithContext::createOptional(LabeledFormatInterface::class),
      ],
      [
        t('Views display'),
        t('Label format'),
      ]);
  }

  /**
   * @param string $viewNameWithDisplayId
   * @param \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface $labeledFormat
   *
   * @return \Drupal\renderkit8\BuildProvider\BuildProvider_ViewsDisplay|null
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
   * @param \Drupal\renderkit8\LabeledFormat\LabeledFormatInterface $labeledFormat
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
