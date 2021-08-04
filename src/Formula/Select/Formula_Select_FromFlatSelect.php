<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;

class Formula_Select_FromFlatSelect implements Formula_SelectInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface $decorated
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
