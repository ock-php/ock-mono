<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Donquixote\ObCK\Formula\Textfield\Formula_Textfield_IntegerInRange;
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
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function createFormula(CfContextInterface $context = NULL): Formula_GroupValInterface {

    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new Formula_Textfield_IntegerInRange(1, 100),
        Formula_IfaceWithContext::create(EntitiesListFormatInterface::class, $context),
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
  public static function create($n, EntitiesListFormatInterface $entitiesListFormat): self {
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
  public function buildEntity(EntityInterface $entity): array {
    return $this->entitiesListFormat->entitiesBuildList(
      array_fill(0, $this->n, $entity));
  }
}
