<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Text\TextInterface;

class Formula_Select_FromFlatSelect implements Formula_SelectInterface {

  /**
   * @var \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $decorated
   */
  public function __construct(Formula_FlatSelectInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptGroups(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(?string $group_id): array {
    return $group_id === NULL
      ? $this->decorated->getOptions()
      : [];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return $this->decorated->idIsKnown($id);
  }

}
