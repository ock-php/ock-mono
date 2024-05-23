<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Ock\Ock\Attribute\Parameter\OckFormulaFromClass;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\Formula\Formula_ViewsDisplay_Id_EntityIdArg;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
use Drupal\views\Views;

/**
 * Show a view (from "views" module) for the entity.
 */
class EntityDisplay_ViewsDisplay extends EntityDisplayBase {

  /**
   * @param string $id
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledEntityBuildProcessor
   *
   * @return self|null
   */
  #[OckPluginInstance('viewsDisplay', 'Views display')]
  public static function create(
    #[OckOption('views_display', 'Views display')]
    #[OckFormulaFromClass(Formula_ViewsDisplay_Id_EntityIdArg::class)]
    string $id,
    #[OckOption('label_format', 'Label format')]
    LabeledEntityBuildProcessorInterface $labeledEntityBuildProcessor = NULL,
  ): ?self {
    [$view_name, $display_id] = explode(':', $id);
    // No further checking at this point.
    return new self(
      $view_name,
      $display_id,
      $labeledEntityBuildProcessor);
  }

  /**
   * @param string $viewName
   * @param string $displayId
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface $labeledDisplay
   */
  public function __construct(
    private readonly string $viewName,
    private readonly string $displayId,
    private readonly ?LabeledEntityBuildProcessorInterface $labeledDisplay = NULL,
  ) {}

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    try {
      return $this->doBuildEntity($entity);
    }
    catch (\Exception $e) {
      // @todo Log this.
      return [];
    }
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   * @throws \Exception
   */
  private function doBuildEntity(EntityInterface $entity): array {
    $etid = $entity->id()
      ?? $this->fail('Entity has no id.');

    $view = Views::getView($this->viewName)
      ?? $this->fail('View not found.');

    $view->setDisplay($this->displayId)
      ?: $this->fail('Cannot set display id.');

    $view->initHandlers();
    $arguments = $view->argument;

    $argPlugin = array_shift($arguments)
      ?? $this->fail('No arguments.');

    $arguments && $this->fail('More than one argument.');

    $argType = $argPlugin->options['validate']['type']
      ?? $this->fail('No validation type in argument.');

    if ($argType !== 'entity:' . $entity->getEntityTypeId()) {
      // Entity type does not match.
      // This is a non-exceptional case, simply show nothing.
      return [];
    }

    $args = [$etid];

    // @todo Some of this might not be required?
    $view->setArguments($args);
    $view->preExecute();
    $view->execute();

    $content = $view->buildRenderable($this->displayId, $args);

    if (NULL === $this->labeledDisplay) {
      return $content;
    }

    $label = $view->getTitle();

    if (!$label) {
      return $content;
    }

    return $this->labeledDisplay->buildAddLabelWithEntity(
      $content,
      $entity,
      $label,
    );
  }

  /**
   * @param string $message
   *
   * @return never
   * @throws \Exception
   */
  private function fail(string $message): never {
    throw new \Exception($message);
  }

}
