<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Donquixote\Cf\Text\TextInterface;

class CfSchema_Select_FromFlatSelect implements CfSchema_SelectInterface {

  /**
   * @var \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface $decorated
   */
  public function __construct(CfSchema_FlatSelectInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    return ['' => $this->decorated->getOptions()];
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
