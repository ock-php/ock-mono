<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Group;

use Donquixote\Ock\Exception\GeneratorException_UnsupportedConfiguration;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface;
use Donquixote\Adaptism\Util\MessageUtil;

class V2V_Group_EmptyWithValueProvider implements V2V_GroupInterface {

  /**
   * @param \Donquixote\Ock\Formula\ValueProvider\Formula_ValueProviderInterface $valueProvider
   */
  public function __construct(
    private readonly Formula_ValueProviderInterface $valueProvider,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {

    if ([] !== $itemsPhp) {
      throw new GeneratorException_UnsupportedConfiguration(
        sprintf(
          'Expected an empty array, found %s.',
          MessageUtil::formatValue($itemsPhp)));
    }

    return $this->valueProvider->getPhp();
  }

}
