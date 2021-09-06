<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;
use Drupal\renderkit\Schema\CfSchema_ViewIdWithDisplayId;
use Drupal\views\Views;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'doCreate',
      [
        CfSchema_ViewIdWithDisplayId::createNoArgs(),
        CfSchema::ifaceOptional(LabeledFormatInterface::class),
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

    if (NULL === $view = Views::getView($this->viewName)) {
      return [];
    }

    if (FALSE === $view->setDisplay($this->displayId)) {
      return [];
    }

    // @todo Some of this might not be required?
    $view->preExecute();
    $view->execute();

    $build = $view->buildRenderable($this->displayId);

    if (NULL === $this->labeledFormat) {
      return $build;
    }

    $label = $view->getTitle();

    if (empty($label)) {
      return $build;
    }

    return $this->labeledFormat->buildAddLabel($build, $label);
  }
}
