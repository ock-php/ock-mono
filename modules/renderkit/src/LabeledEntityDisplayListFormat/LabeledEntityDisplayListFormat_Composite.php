<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledEntityDisplayListFormat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplayListFormat\EntityDisplayListFormatInterface;
use Drupal\renderkit\LabeledEntityBuildProcessor\LabeledEntityBuildProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckAdaptee;
use Ock\Ock\Attribute\Parameter\OckOption;
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
  public static function createFromLabeledFormat(
    #[OckAdaptee]
    LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
  ): self {
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
  public static function createFromListFormat(
    #[OckAdaptee]
    EntityDisplayListFormatInterface $listFormat = NULL,
  ): self {
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
    #[OckOption('labeledFormat', 'Labeled format')]
    LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    #[OckOption('listFormat', 'List format')]
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
    #[OckOption('outerProcessor', 'Outer processor')]
    private readonly ?EntityBuildProcessorInterface $outerProcessor = NULL,
    #[OckOption('labeledFormat', 'Labeled format')]
    private readonly ?LabeledEntityBuildProcessorInterface $labeledFormat = NULL,
    #[OckOption('innerProcessor', 'Inner processor')]
    private readonly ?EntityBuildProcessorInterface $innerProcessor = NULL,
    #[OckOption('listFormat', 'List format')]
    private readonly ?EntityDisplayListFormatInterface $listFormat = NULL,
    #[OckOption('itemProcessor', 'Item processor')]
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
