<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;

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
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
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
   * {@inheritdoc}
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
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity)) {
      return [];
    }
    return $this->relatedEntityDisplay->buildEntity($relatedEntity);
  }

}
