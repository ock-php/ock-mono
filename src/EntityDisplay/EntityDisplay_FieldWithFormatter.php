<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface;
use Drupal\renderkit8\Schema\CfSchema_EntityDisplay_FieldWithFormatter;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityDisplay_FieldWithFormatter extends EntityDisplayBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $display;

  /**
   * @var \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null
   */
  private $fieldDisplayProcessor;

  /**
   * @var string|null
   */
  private $langcode;

  /**
   * @CfrPlugin("fieldWithFormatter", @t("Field with formatter *"))
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function schema() {
    return new CfSchema_EntityDisplay_FieldWithFormatter();
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $field_name
   * @param array $display
   * @param \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $fieldDisplayProcessor
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    $field_name,
    array $display = [],
    FieldDisplayProcessorInterface $fieldDisplayProcessor = NULL
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->fieldName = $field_name;
    $this->display = $display + ['label' => 'hidden'];
    $this->fieldDisplayProcessor = $fieldDisplayProcessor;
  }

  /**
   * Sets the field formatter.
   *
   * @param string $name
   *   The machine name of the field formatter.
   * @param array $settings
   *   The formatter settings.
   *
   * @return $this
   */
  public function setFormatter($name, array $settings = NULL) {
    $this->display['type'] = $name;
    if (NULL !== $settings) {
      $this->display['settings'] = $settings;
    }
    return $this;
  }

  /**
   * @param string $label_position
   *   The default implementation supports 'above', 'inline' and 'hidden'.
   *   Default in core is 'above', but default here is 'hidden'.
   */
  public function setLabelPosition($label_position) {
    $this->display['label'] = $label_position;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  public function buildEntity(EntityInterface $entity) {

    if (!$entity instanceof FieldableEntityInterface) {
      return [];
    }

    $fieldItemList = $entity->get($this->fieldName);

    if ($fieldItemList->isEmpty()) {
      return [];
    }

    $builder = $this->entityTypeManager->getViewBuilder(
      $entity->getEntityTypeId());

    $build = $builder->viewField(
      $fieldItemList,
      $this->display);

    if (NULL !== $this->fieldDisplayProcessor) {
      $build = $this->fieldDisplayProcessor->process($build);
    }

    return $build;
  }
}
