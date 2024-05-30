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
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private ?EntityBuildProcessorInterface $outerProcessor;

  /**
   * @var \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null
   */
  private ?LabeledEntityBuildProcessorInterface $labeledFormat;

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private ?EntityBuildProcessorInterface $innerProcessor;

  /**
   * @var \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|null
   */
  private ?EntityDisplayListFormatInterface $listFormat;

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private ?EntityBuildProcessorInterface $itemProcessor;

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
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface $outerProcessor
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface $labeledFormat
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface $innerProcessor
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface $listFormat
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null $itemProcessor
   */
  public function __construct(
    EntityBuildProcessorInterface $outerProcessor = NULL,
    LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    EntityBuildProcessorInterface $innerProcessor = NULL,
    EntityDisplayListFormatInterface $listFormat = NULL,
    EntityBuildProcessorInterface $itemProcessor = NULL
  ) {
    $this->outerProcessor = $outerProcessor;
    $this->labeledFormat = $labeledFormat;
    $this->innerProcessor = $innerProcessor;
    $this->listFormat = $listFormat;
    $this->itemProcessor = $itemProcessor;
  }

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
