<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImage;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;
use Ock\Ock\Attribute\Plugin\OckPluginFormula;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;

class EntityImage_Related implements EntityImageInterface {

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(self::class, 'related', 'Image from related entity')]
  public static function createFormula(): FormulaInterface {
    return Formula::group()
      ->add(
        'relation',
        Text::t('Entity relation'),
        // @todo Inject context?
        Formula::iface(EntityToEntityInterface::class),
      )
      ->addDynamicFormula(
        'image',
        Text::t('Related entity image'),
        ['relation'],
        static function (EntityToEntityInterface $relation): FormulaInterface {
          // @todo Context from target type.
          $targetType = $relation->getTargetType();
          unset($targetType);
          return Formula::iface(EntityImageInterface::class);
        },
      )
      ->construct(self::class);
  }

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $relatedEntityImage
   */
  public function __construct(
    private readonly EntityToEntityInterface $entityToEntity,
    private readonly EntityImageInterface $relatedEntityImage,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
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

    return $this->relatedEntityImage->buildEntities($relatedEntities);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity)) {
      return [];
    }
    return $this->relatedEntityImage->buildEntity($relatedEntity);
  }
}
