<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\FreeParameters\Formula_FreeParameters;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

#[RegisterService(self::SERVICE_ID)]
class Formula_EntityType implements Formula_DrupalSelectInterface {

  const SERVICE_ID = 'renderkit.formula.entity_type';

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
    #[DrupalService('entity_type.repository')]
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
