<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\Select;

use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;

class Formula_Select_FromFlatSelect implements Formula_SelectInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $decorated
   */
  public function __construct(
    private readonly Formula_FlatSelectInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptionsMap(): array {
    return array_fill_keys(array_keys($this->decorated->getOptions()), '');
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return $this->decorated->idIsKnown($id);
  }

  public function groupIdGetLabel(int|string $groupId): ?TextInterface {
    return NULL;
  }

}
