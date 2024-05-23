<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParameters;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

#[Service(self::class)]
class Formula_EntityType implements Formula_DrupalSelectInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  public static function proxy(): FormulaInterface {
    return Formula_FreeParameters::fromClass(self::class);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(
    #[GetService('entity_type.repository')]
    private readonly EntityTypeRepositoryInterface $entityTypeRepository,
  ) {}

  /**
   * @return string[]
   */
  public function getGroupedOptions(): array {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    return $options[$id] ?? null;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    $options = $this->entityTypeRepository->getEntityTypeLabels();
    return isset($options[$id]);
  }

}
