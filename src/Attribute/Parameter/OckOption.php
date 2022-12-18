<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Attribute\Parameter;

use Donquixote\Ock\Contract\LabelHavingInterface;
use Donquixote\Ock\Contract\NameHavingInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

#[\Attribute(\Attribute::TARGET_PARAMETER|\Attribute::TARGET_PROPERTY)]
class OckOption implements NameHavingInterface, LabelHavingInterface {

  public function __construct(
    private readonly string $name,
    private readonly string $label,
    private readonly bool $translate = true,
  ) {}

  public function getLabel(): TextInterface {
    return Text::tIf($this->label, $this->translate);
  }

  public function getName(): string {
    return $this->name;
  }

}
