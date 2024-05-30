<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('compositeAdvanced', 'Composite, advanced')]
class LabeledEntityDisplayListFormat_Composite implements LabeledEntityDisplayListFormatInterface {

  /**
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledFormat
   *
   * @return self
   *
   * @todo Mark as adapter/inline.
   */
  #[OckPluginInstance('labeledFormat', 'Labeled format')]
  public static function createFromLabeledFormat(LabeledEntityBuildProcessorInterface $labeledFormat = NULL): self {
    return new self(NULL, $labeledFormat, NULL, NULL);
  }

  /**
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|NULL $listFormat
   *
   * @return self
   *
   * @todo Mark as adapter/inline.
   */
  #[OckPluginInstance('listFormat', 'List format')]
  public static function createFromListFormat(EntityDisplayListFormatInterface $listFormat = NULL): self {
    return new self(NULL, NULL, NULL, $listFormat);
  }

  /**
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledFormat
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|null $listFormat
   *
   * @return self
   */
  #[OckPluginInstance('composite', 'Composite')]
  public static function createSimple(
    LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    EntityDisplayListFormatInterface $listFormat = NULL
  ): self {
    return new self(NULL, $labeledFormat, NULL, $listFormat);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null $outerProcessor
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledFormat
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null $innerProcessor
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|null $listFormat
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null $itemProcessor
   */
  public function __construct(
    private readonly ?EntityBuildProcessorInterface $outerProcessor = NULL,
    private readonly ?LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    private readonly ?EntityBuildProcessorInterface $innerProcessor = NULL,
    private readonly ?EntityDisplayListFormatInterface $listFormat = NULL,
    private readonly ?EntityBuildProcessorInterface $itemProcessor = NULL
  ) {}

  /**
   * {@inheritdoc}
   */
  public function build(array $builds, string $entityType, EntityInterface $entity, string $label): array {

    if (NULL !== $this->itemProcessor) {
      foreach ($builds as $delta => $build) {
        $builds[$delta] = $this->itemProcessor->processEntityBuild($build, $entity);
      }
    }

    $build = (NULL !== $this->listFormat)
      ? $this->listFormat->buildListWithEntity($builds, $entityType, $entity)
      : $builds;

    if (NULL !== $this->innerProcessor) {
      $build = $this->innerProcessor->processEntityBuild($build, $entity);
    }

    if (NULL !== $this->labeledFormat) {
      $build = $this->labeledFormat->buildAddLabelWithEntity($build, $entity, $label);
    }

    if (NULL !== $this->outerProcessor) {
      $build = $this->outerProcessor->processEntityBuild($build, $entity);
    }

    return $build;
  }
}
