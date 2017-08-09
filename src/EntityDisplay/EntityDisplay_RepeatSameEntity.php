<?php

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Schema\Textfield\CfSchema_Textfield_IntegerInRange;
use Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface;

class EntityDisplay_RepeatSameEntity extends EntityDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface
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

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        CfSchema_IfaceWithContext::create(EntitiesListFormatInterface::class, $context),
        new CfSchema_Textfield_IntegerInRange(1, 100),
      ],
      [
        t('Entities list format'),
        t('Number of repetitions'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\EntitiesListFormat\EntitiesListFormatInterface $entitiesListFormat
   * @param int $n
   */
  public function __construct(EntitiesListFormatInterface $entitiesListFormat, $n) {
    $this->entitiesListFormat = $entitiesListFormat;
    $this->n = $n;
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
    return $this->entitiesListFormat->entitiesBuildList(
      $entity_type,
      array_fill(0, $this->n, $entity));
  }
}
