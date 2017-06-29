<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\cfrapi\CfrSchema\Group\GroupSchema_Callback;
use Drupal\cfrapi\CfrSchema\Iface\IfaceSchema;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

class EntityImage_Related implements EntityImageInterface {

  /**
   * @var \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  private $entityToEntity;

  /**
   * @var \Drupal\renderkit\EntityImage\EntityImageInterface
   */
  private $relatedEntityImage;

  /**
   * @var string
   */
  private $relatedEntityType;

  /**
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public static function createSchema(CfrContextInterface $context = NULL) {

    return GroupSchema_Callback::createFromClass(
      __CLASS__,
      [
        new IfaceSchema(EntityToEntityInterface::class, $context),
        // This one is without context, because we no longer know the entity type.
        new IfaceSchema(EntityImageInterface::class),
      ],
      [
        t('Entity relation'),
        t('Related entity image provider'),
      ]);
  }

  /**
   * @CfrPlugin(
   *   id = "related",
   *   label = "Image from related entity"
   * )
   *
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator(CfrContextInterface $context = NULL) {
    /** @var \Drupal\cfrplugin\Hub\CfrPluginHubInterface $hub */
    $hub = cfrplugin();
    return Configurator_CallbackConfigurable::createFromClassName(
      __CLASS__,
      [
        $hub->interfaceGetConfigurator(EntityToEntityInterface::class, $context),
        // This one is without context, because we no longer know the entity type.
        $hub->interfaceGetConfigurator(EntityImageInterface::class),
      ],
      [
        t('Entity relation'),
        t('Related entity image provider'),
      ]
    );
  }

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $relatedEntityImage
   */
  public function __construct(EntityToEntityInterface $entityToEntity, EntityImageInterface $relatedEntityImage) {
    $this->entityToEntity = $entityToEntity;
    $this->relatedEntityImage = $relatedEntityImage;
    $this->relatedEntityType = $entityToEntity->getTargetType();
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity($entity_type, $entity) {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity_type, $entity)) {
      return [];
    }
    return $this->relatedEntityImage->buildEntity($this->relatedEntityType, $relatedEntity);
  }

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *   Each render array must contain '#theme' => 'image'.
   */
  public function buildEntities($entityType, array $entities) {
    $entities = $this->entityToEntity->entitiesGetRelated($entityType, $entities);
    return $this->relatedEntityImage->buildEntities($this->relatedEntityType, $entities);
  }
}
