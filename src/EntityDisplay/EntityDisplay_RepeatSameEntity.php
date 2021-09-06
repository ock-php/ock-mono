<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Textfield\CfSchema_Textfield_IntegerInRange;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface;

class EntityDisplay_RepeatSameEntity extends EntityDisplayBase {

  /**
   * @var \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface
   */
  private $entitiesListFormat;

  /**
   * @var int
   */
  private $n;

  /**
   * @CfrPlugin("repeatSameEntity", "Repeat the same entity")
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createSchema(CfContextInterface $context = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_Textfield_IntegerInRange(1, 100),
        CfSchema_IfaceWithContext::create(EntitiesListFormatInterface::class, $context),
      ],
      [
        t('Number of repetitions'),
        t('Entities list format'),
      ]);
  }

  /**
   * @param int|string $n
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   *
   * @return self
   */
  public static function create($n, EntitiesListFormatInterface $entitiesListFormat) {
    return new self($entitiesListFormat, (int) $n);
  }

  /**
   * @param \Drupal\renderkit\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   * @param int $n
   */
  public function __construct(EntitiesListFormatInterface $entitiesListFormat, $n) {
    $this->entitiesListFormat = $entitiesListFormat;
    $this->n = $n;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    return $this->entitiesListFormat->entitiesBuildList(
      array_fill(0, $this->n, $entity));
  }
}
