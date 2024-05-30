<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_WithBuildProcessor extends EntitiesDisplayBase {

  /**
   * @CfrPlugin(
   *   id = "processedEntityDisplay",
   *   label = @Translate("Processed entity display")
   * )
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityDisplayInterface $entityDisplay, BuildProcessorInterface $processor = NULL): EntityDisplayInterface {
    return NULL !== $processor
      ? new static($entityDisplay, $processor)
      : $entityDisplay;
  }

  /**
   * ProcessorEntityDisplayDecorator constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(
    private readonly EntityDisplayInterface $entityDisplay,
    private readonly BuildProcessorInterface $processor,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $builds = $this->entityDisplay->buildEntities($entities);
    foreach ($builds as $delta => $build) {
      $builds[$delta] = $this->processor->process($build);
    }
    return $builds;
  }

}
