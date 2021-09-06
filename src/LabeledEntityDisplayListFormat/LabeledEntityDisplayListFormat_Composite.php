<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;

/**
 * @CfrPlugin(
 *   id = "compositeAdvanced",
 *   label = @t("Composite, advanced")
 * )
 */
class LabeledEntityDisplayListFormat_Composite implements LabeledEntityDisplayListFormatInterface {

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private $outerProcessor;

  /**
   * @var \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null
   */
  private $labeledFormat;

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private $innerProcessor;

  /**
   * @var \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|null
   */
  private $listFormat;

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|null
   */
  private $itemProcessor;

  /**
   * @CfrPlugin(
   *   id = "labeledFormat",
   *   label = @t("Labeled format"),
   *   inline = true
   * )
   *
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface $labeledFormat
   *
   * @return \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  public static function createFromLabeledFormat(LabeledEntityBuildProcessorInterface $labeledFormat = NULL) {
    return new self(NULL, $labeledFormat, NULL, NULL);
  }

  /**
   * @CfrPlugin(
   *   id = "listFormat",
   *   label = @t("List format"),
   *   inline = true
   * )
   *
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface $listFormat
   *
   * @return \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  public static function createFromListFormat(EntityDisplayListFormatInterface $listFormat = NULL) {
    return new self(NULL, NULL, NULL, $listFormat);
  }

  /**
   * @CfrPlugin(
   *   id = "composite",
   *   label = @t("Composite")
   * )
   *
   * @param \Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface|null $labeledFormat
   * @param \Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface|null $listFormat
   *
   * @return \Drupal\renderkit\LabeledEntityDisplayListFormat\LabeledEntityDisplayListFormatInterface
   */
  public static function createSimple(
    LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    EntityDisplayListFormatInterface $listFormat = NULL
  ) {
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
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param string $label
   *   A label, e.g. for
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, $entityType, $entity, $label): array {

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
