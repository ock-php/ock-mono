<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Ock\Ock\Attribute\Parameter\OckFormulaFromCall;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\Formula\Formula_ViewIdWithDisplayId;
use Drupal\renderkit\LabeledFormat\LabeledFormatInterface;
use Drupal\views\Views;

class BuildProvider_ViewsDisplay implements BuildProviderInterface {

  /**
   * @param string $viewNameWithDisplayId
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface|null $labeledFormat
   *
   * @return self|null
   */
  #[OckPluginInstance('viewsDisplay', 'Views display')]
  public static function doCreate(
    #[OckOption('views_display', 'Views display')]
    #[OckFormulaFromCall([Formula_ViewIdWithDisplayId::class, 'createNoArgs'])]
    string $viewNameWithDisplayId,
    #[OckOption('label_format', 'Label format')]
    LabeledFormatInterface $labeledFormat = NULL,
  ): ?self {
    [$view_name, $display_id] = explode(':', $viewNameWithDisplayId . ':');
    if ('' === $view_name || '' === $display_id) {
      return NULL;
    }
    // No further checking at this point.
    return new self($view_name, $display_id, $labeledFormat);
  }

  /**
   * Constructor.
   *
   * @param string $viewName
   * @param string $displayId
   * @param \Drupal\renderkit\LabeledFormat\LabeledFormatInterface|null $labeledFormat
   */
  public function __construct(
    private readonly string $viewName,
    private readonly string $displayId,
    private readonly ?LabeledFormatInterface $labeledFormat = NULL,
  ) {}

  /**
   * @return array
   *   A render array.
   */
  public function build(): array {

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
