<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

#[OckPluginInstance('related', 'Related entity')]
class EntityDisplay_RelatedEntity implements EntityDisplayInterface {

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $relatedEntityDisplay
   */
  public function __construct(
    #[OckOption('relation', 'Entity relation')]
    private readonly EntityToEntityInterface $entityToEntity,
    // @todo Reset context entity type and bundle for this formula.
    #[OckOption('display', 'Display for the related entity')]
    private readonly EntityDisplayInterface $relatedEntityDisplay,
  ) {}

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   *
   * @todo Make this the real formula.
   */
  public function formula(): FormulaInterface {
    return Formula::group()
      ->add(
        'relation',
        Text::t('Entity relation'),
        Formula::iface(EntityToEntityInterface::class),
      )
      ->addDynamicFormula(
        'display',
        Text::t('Display for the related entity'),
        ['relation'],
        // @todo Insert target entity type as context.
        fn (EntityToEntityInterface $relation) => Formula::iface(
          EntityDisplayInterface::class,
        ),
      )
      ->construct(self::class, ['relation', 'display']);
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   */
  public function buildEntities(array $entities): array {

    $relatedEntities = [];
    foreach ($entities as $delta => $entity) {
      if (NULL !== $related = $this->entityToEntity->entityGetRelated($entity)) {
        $relatedEntities[$delta] = $related;
      }
    }

    return $this->relatedEntityDisplay->buildEntities($relatedEntities);
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity)) {
      return [];
    }
    return $this->relatedEntityDisplay->buildEntity($relatedEntity);
  }
}
