<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityImages;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Boolean\Formula_Boolean_YesNo;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Util\ImageFieldUtil;

class EntityImages_ImageField implements EntityImagesInterface {

  /**
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $fieldNameFormula
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  #[OckPluginFormula(self::class, 'imageField', 'Image field')]
  public static function createFormula(
    #[GetService]
    Formula_EtDotFieldName $fieldNameFormula,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'image_field',
        Text::t('Image field'),
        $fieldNameFormula->withAllowedFieldTypes(['image']),
      )
      ->addDynamicValues(
        ['entity_type', 'field_name'],
        ['image_field'],
        fn (string $etDotFieldName) => explode('.', $etDotFieldName),
      )
      ->add(
        'use_default_image',
        Text::t('Use default image as fallback'),
        Formula_Boolean_YesNo::create(true),
      )
      ->construct(self::class, [
        'entity_type',
        'field_name',
        'use_default_image',
      ]);
  }

  /**
   * Constructor.
   *
   * @param string $entityTypeId
   * @param string $fieldName
   * @param bool $useDefaultImage
   */
  public function __construct(
    private readonly string $entityTypeId,
    private readonly string $fieldName,
    private readonly bool $useDefaultImage = TRUE,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Render array for one entity.
   */
  public function entityGetImages(EntityInterface $entity): array {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return [];
    }

    if (!$entity instanceof FieldableEntityInterface) {
      return [];
    }

    $items = $entity->get($this->fieldName);

    if (!$items instanceof FileFieldItemList) {
      return [];
    }

    if ($this->useDefaultImage) {
      try {
        $items = ImageFieldUtil::itemsGetItems($items);
      }
      catch (EntityStorageException $e) {
        // @todo Log this.
        unset($e);
        return [];
      }
    }

    $builds = [];
    foreach ($items as $delta => $item) {

      if (!$item instanceof ImageItem) {
        continue;
      }

      $builds[$delta] = ImageFieldUtil::buildImageFieldItem($item);
    }

    return $builds;
  }
}
