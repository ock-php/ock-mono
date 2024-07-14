<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\ock\Attribute\DI\PublicService;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\FreeParameters\Formula_FreeParameters;

#[PublicService]
class Formula_EntityType implements Formula_DrupalSelectInterface {

  /**
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function proxy(): FormulaInterface {
    return Formula_FreeParameters::fromClass(self::class);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(
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
