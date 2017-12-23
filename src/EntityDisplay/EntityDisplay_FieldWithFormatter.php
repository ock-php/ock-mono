<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface;
use Drupal\renderkit8\Schema\CfSchema_EntityDisplay_FieldWithFormatter;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityDisplay_FieldWithFormatter extends EntityDisplay_FieldItemsBase {

  /**
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  private $viewBuilder;

  /**
   * @var array
   */
  private $display;

  /**
   * @var \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null
   */
  private $fieldDisplayProcessor;

  /**
   * @CfrPlugin("fieldWithFormatter", @t("Field with formatter"))
   *
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function schema($entityType = NULL, $bundle = NULL) {
    return CfSchema_EntityDisplay_FieldWithFormatter::createValSchema(
      $entityType,
      $bundle);
  }

  /**
   * @param string $entity_type
   * @param string $field_name
   * @param array $display
   * @param \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $fieldDisplayProcessor
   *
   * @return self
   */
  public static function create(
    $entity_type,
    $field_name,
    array $display = [],
    FieldDisplayProcessorInterface $fieldDisplayProcessor = NULL
  ) {

    return new self(
      \Drupal::service('entity_type.manager'),
      $entity_type,
      $field_name,
      $display,
      $fieldDisplayProcessor);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entity_type
   * @param string $field_name
   * @param array $display
   * @param \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $fieldDisplayProcessor
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    $entity_type,
    $field_name,
    array $display = [],
    FieldDisplayProcessorInterface $fieldDisplayProcessor = NULL
  ) {
    $this->viewBuilder = $entityTypeManager->getViewBuilder($entity_type);
    $this->display = $display + ['label' => 'hidden'];
    $this->fieldDisplayProcessor = $fieldDisplayProcessor;
    parent::__construct($entity_type, $field_name);
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $fieldItemList
   *
   * @return array
   */
  protected function buildFieldItems(FieldItemListInterface $fieldItemList) {

    if ($fieldItemList->isEmpty()) {
      return [];
    }

    $build = $this->viewBuilder->viewField(
      $fieldItemList,
      $this->display);

    if (NULL !== $this->fieldDisplayProcessor) {
      $build = $this->fieldDisplayProcessor->process($build);
    }

    return $build;
  }
}
